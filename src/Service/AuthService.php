<?php

namespace App\Service;

use App\Dto\Inputs\SigninDto;
use App\Dto\Inputs\SignupDto;
use App\Dto\Outputs\AuthResponseDto;
use App\Dto\Outputs\UserDto;
use App\Entity\User;
use App\Entity\UserToken;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class AuthService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly JWTTokenManagerInterface $jwtManager,
    ) {
    }

    /** SIGNUP **/
    public function signup(SignupDto $dto): UserDto
    {
        if ($this->userRepository->findOneBy(['email' => $dto->getEmail()])) {
            throw new \DomainException('Email already used');
        }

        $user = new User();
        $user->setEmail($dto->getEmail())
             ->setFirstname($dto->getFirstname())
             ->setLastname($dto->getLastname())
             ->setPassword(
                 $this->passwordHasher->hashPassword($user, $dto->getPassword())
             );

        $this->em->persist($user);
        $this->em->flush();

        return UserDto::fromEntity($user);
    }

    /** SIGNIN **/
    public function signin(SigninDto $dto): AuthResponseDto
    {
        $user = $this->userRepository->findOneBy(['email' => $dto->getEmail()]);

        if (!$user || !$this->passwordHasher->isPasswordValid($user, $dto->getPassword())) {
            throw new \DomainException('Invalid credentials');
        }

        $accessToken = $this->jwtManager->create($user);

        $refreshTokenPlain = bin2hex(random_bytes(32));

        $refreshToken = new UserToken();
        $refreshToken->setUser($user)
            ->setToken(hash('sha256', $refreshTokenPlain))
            ->setExpiresAt(new \DateTimeImmutable('+30 days'));

        $this->em->persist($refreshToken);
        $this->em->flush();

        $userDto = UserDto::fromEntity($user);

        return new AuthResponseDto(
            $accessToken,
            $refreshTokenPlain,
            $userDto
        );
    }

    /** REFRESH **/
    public function refresh(string $tokenPlain): AuthResponseDto
    {
        if (!$tokenPlain) {
            throw new \DomainException('Refresh token required');
        }

        $tokenHashed = hash('sha256', $tokenPlain);

        $oldRefreshToken = $this->em->getRepository(UserToken::class)
                                    ->findOneBy(['token' => $tokenHashed]);

        if (!$oldRefreshToken) {
            throw new \DomainException('Invalid refresh token');
        }

        if ($oldRefreshToken->isExpired()) {
            throw new \DomainException('Refresh token expired');
        }

        $user = $oldRefreshToken->getUser();

        $this->em->remove($oldRefreshToken);

        // rotation du refreshToken
        $newRefreshTokenPlain = bin2hex(random_bytes(32));
        $newRefreshToken = new UserToken();
        $newRefreshToken->setUser($user)
            ->setToken(hash('sha256', $newRefreshTokenPlain))
            ->setExpiresAt(new \DateTimeImmutable('+30 days'));

        $this->em->persist($newRefreshToken);
        $this->em->flush();

        $accessToken = $this->jwtManager->create($user);

        return new AuthResponseDto(
            $accessToken,
            $newRefreshTokenPlain,
            UserDto::fromEntity($user)
        );
    }

    /** LOGOUT **/
    public function logout(?string $tokenPlain): void
    {
        if (!$tokenPlain) {
            return;
        }

        $tokenHashed = hash('sha256', $tokenPlain);

        $refreshToken = $this->em->getRepository(UserToken::class)
                                 ->findOneBy(['token' => $tokenHashed]);

        if ($refreshToken) {
            $this->em->remove($refreshToken);
            $this->em->flush();
        }
    }
}
