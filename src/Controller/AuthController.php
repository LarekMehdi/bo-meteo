<?php

namespace App\Controller;

use App\Dto\Inputs\SigninDto;
use App\Dto\Inputs\SignupDto;
use App\Service\AuthService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/auth')]
final class AuthController extends AbstractController
{
    public function __construct(
        private readonly AuthService $authService,
    ) {
    }

    /** SIGNUP **/
    #[Route('/signup', name: 'auth_signup', methods: ['POST'])]
    public function signup(SignupDto $dto): JsonResponse
    {
        $userDto = $this->authService->signup($dto);

        return $this->json($userDto, 201);

        return $this->json(['error' => $e->getMessage()], 409);
    }

    /** SIGNIN **/
    #[Route('/signin', name: 'auth_signin', methods: ['POST'])]
    public function signin(SigninDto $dto): JsonResponse
    {
        $responseDto = $this->authService->signin($dto);

        return $this->json($responseDto, 200);
    }

    /** REFRESH **/
    #[Route('/refresh', name: 'auth_refresh', methods: ['GET'])]
    public function refresh(Request $request): JsonResponse
    {
        $tokenPlain = $request->headers->get('X-Refresh-Token');

        $responseDto = $this->authService->refresh($tokenPlain);

        return $this->json($responseDto, 200);

        $message = $e->getMessage();

        $status = match ($message) {
            'Refresh token required' => 400,
            'Invalid refresh token' => 401,
            'Refresh token expired' => 403,
            default => 500,
        };

        return $this->json(['error' => $message], $status);
    }

    /** LOGOUT **/
    #[Route('/logout', name: 'auth_logout', methods: ['DELETE'])]
    public function logout(Request $request): JsonResponse
    {
        $tokenPlain = $request->headers->get('X-Refresh-Token');

        $this->authService->logout($tokenPlain);

        return $this->json(['message' => 'Logged out'], 200);
    }
}
