<?php

namespace App\Controller;

use App\Dto\Inputs\CityFilterDto;
use App\Dto\Inputs\ForecastFilterDto;
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

    #[Route('', name: 'forecast', methods: ['GET'])]
    public function getForecast(ForecastFilterDto $filter): JsonResponse
    {
        $forecastDto = $this->forecastService->fetchForecast($filter);

        return $this->json($forecastDto);
    }

    #[Route('/city', name: 'forecast_city', methods: ['GET'])]
    public function searchCities(CityFilterDto $filter): JsonResponse
    {
        $cityDtos = $this->forecastService->fetchCities($filter);

        return $this->json($cityDtos);
    }
}
