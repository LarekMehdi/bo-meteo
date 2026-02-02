<?php

namespace App\Controller;

use App\Dto\Inputs\CityFilterDto;
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
    #[Route('/', name: 'forecast', methods: ['GET'])]
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

    #[Route('/city', name: 'forecast_city', methods: ['GET'])]
    public function searchCities(CityFilterDto $filter): JsonResponse
    {
        try {
            $cityDtos = $this->forecastService->fetchCities($filter);

            return $this->json($cityDtos);
        } catch (OpenMeteoException $e) {
            return $this->json(['error' => 'External API error'], $e->getCode());
        } catch (\Exception $e) {
            return $this->json(['error' => 'Unexpected error'], 500);
        }
    }
}
