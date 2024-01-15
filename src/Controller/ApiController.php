<?php

namespace App\Controller;

use App\Repository\FlightRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class ApiController extends AbstractController
{
    #[Route('/directions/{pointOfDeparture}', name: 'directions', methods: ['get'])]
    public function directions(string $pointOfDeparture, FlightRepository $flightRepository): JsonResponse
    {
        $flights = $flightRepository->searchFlightByFrom($pointOfDeparture);
        $result = [];
        if ($flights) {
            foreach ($flights as $flight) {
                $result[] = $flight->getArrivalPoint();
            }
        }
        return $this->json($result, 200);
    }

    #[Route('/dates/{pointOfDeparture}/{arrivalPoint}', name: 'dates', methods: ['get'])]
    public function dates(string $pointOfDeparture, string $arrivalPoint, FlightRepository $flightRepository): JsonResponse
    {
        $flights = $flightRepository->searchFlightByFromAndTo($pointOfDeparture, $arrivalPoint);
        $result = [];
        if ($flights) {
            foreach ($flights as $flight) {
                $result[] = $flight->getDate();
            }
        }
        return $this->json($result, 200);
    }
}
