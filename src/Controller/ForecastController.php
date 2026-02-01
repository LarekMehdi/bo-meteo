<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/forecast')]
final class ForecastController extends AbstractController
{
    #[Route('/', name: 'app_forecast', methods: ['GET'])]
    public function getForecast(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ForecastController.php',
        ]);
    }
}
