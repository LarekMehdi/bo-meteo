<?php

namespace App\Controller;

use App\Dto\Inputs\ForecastFilterDto;
use App\Exception\OpenMeteoException;
use App\Service\ForecastService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/forecast')]
final class ForecastController extends AbstractController
{
    public function __construct(private readonly ForecastService $forecastService)
    {
    }

    // TODO: ExceptionListener
    #[Route('/', name: 'app_forecast', methods: ['GET'])]
    public function getForecast(ForecastFilterDto $filter): JsonResponse
    {
        try {
            $forecastDto = $this->forecastService->fetchForecast($filter);

            return $this->json($forecastDto);
        } catch (OpenMeteoException $e) {
            return $this->json(['error' => 'External API error'], $e->getCode());
        } catch (\Exception $e) {
            return $this->json(['error' => 'Unexpected error'], 500);
        }
    }
}
