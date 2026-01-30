<?php

namespace App\Controller;

use App\Dto\Inputs\SigninDto;
use App\Dto\Inputs\SignupDto;
use App\Dto\Outputs\AuthResponseDto;
use App\Dto\Outputs\UserDto;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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

        $token = $this->jwtManager->create($user);

        $userDto = UserDto::fromEntity($user);

        $responseDto = new AuthResponseDto(
            $token,
            null, // TODO: refreshToken
            $userDto
        );

        return $this->json($responseDto, 200);
    }
}
