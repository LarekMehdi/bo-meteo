<?php

namespace App\Controller;

use App\Dto\Inputs\SigninDto;
use App\Dto\Inputs\SignupDto;
use App\Dto\Outputs\AuthResponseDto;
use App\Dto\Outputs\UserDto;
use App\Entity\User;
use App\Entity\UserToken;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/auth')]
final class AuthController extends AbstractController
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly JWTTokenManagerInterface $jwtManager,
        private readonly EntityManagerInterface $em,
    ) {
    }

    /** SIGNUP **/
    #[Route('/signup', name: 'auth_signup', methods: ['POST'])]
    public function signup(SignupDto $dto): JsonResponse
    {
        if ($this->userRepository->findOneBy(['email' => $dto->email])) {
            return $this->json(['error' => 'Email already used'], 409);
        }

        $user = new User();
        $user->setEmail($dto->email)
             ->setFirstname($dto->firstname)
             ->setLastname($dto->lastname)
             ->setPassword(
                 $this->passwordHasher->hashPassword($user, $dto->password)
             );

        $this->em->persist($user);
        $this->em->flush();

        $responseDto = UserDto::fromEntity($user);

        return $this->json($responseDto, 201);
    }

    /** SIGNIN **/
    #[Route('/signin', name: 'auth_signin', methods: ['POST'])]
    public function signin(SigninDto $dto): JsonResponse
    {
        $user = $this->userRepository->findOneBy(['email' => $dto->email]);
        if (!$user || !$this->passwordHasher->isPasswordValid($user, $dto->password)) {
            return $this->json(['error' => 'Invalid credentials'], 401);
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

        $responseDto = new AuthResponseDto(
            $accessToken,
            $refreshTokenPlain,
            $userDto
        );

        return $this->json($responseDto, 200);
    }

    /** REFRESH **/
    #[Route('/refresh', name: 'auth_refresh', methods: ['GET'])]
    public function refresh(Request $request): JsonResponse
    {
        $tokenPlain = $request->headers->get('X-Refresh-Token');

        if (!$tokenPlain) {
            return $this->json(['error' => 'Refresh token required'], 400);
        }

        $tokenHashed = hash('sha256', $tokenPlain);

        $oldRefreshToken = $this->em->getRepository(UserToken::class)
                                    ->findOneBy(['token' => $tokenHashed]);

        if (!$oldRefreshToken) {
            return $this->json(['error' => 'Invalid refresh token'], 401);
        }

        if ($oldRefreshToken->isExpired()) {
            return $this->json(['error' => 'Refresh token expired'], 403);
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

        $responseDto = new AuthResponseDto(
            $accessToken,
            $newRefreshTokenPlain,
            UserDto::fromEntity($user)
        );

        return $this->json($responseDto);
    }

    /** LOGOUT **/
    #[Route('/logout', name: 'auth_logout', methods: ['DELETE'])]
    public function logout(Request $request): JsonResponse
    {
        $tokenPlain = $request->headers->get('X-Refresh-Token');

        if ($tokenPlain) {
            $tokenHashed = hash('sha256', $tokenPlain);
            $refreshToken = $this->em->getRepository(UserToken::class)
                                     ->findOneBy(['token' => $tokenHashed]);

            if ($refreshToken) {
                $this->em->remove($refreshToken);
                $this->em->flush();
            }
        }

        return $this->json(['message' => 'Logged out'], 200);
    }
}
