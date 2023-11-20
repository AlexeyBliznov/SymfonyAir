<?php

declare(strict_types=1);

namespace App\UseCase\Supervisor\RemoveAirplane;

use App\Repository\AirplaneRepository;
use App\Repository\FlightRepository;
use App\UseCase\Supervisor\RemoveFlight\Handler as RemoveFlightHandler;

readonly class Handler
{
    public function __construct(
        private AirplaneRepository $airplaneRepository,
        private FlightRepository $flightRepository,
        private RemoveFlightHandler $handler
    ) {}

    public function handle(int $id): void
    {
        $airplane = $this->airplaneRepository->find($id);
        $flight = $this->flightRepository->findByAirplane($airplane);
        if ($flight !== null) {
            $this->handler->handle($flight->getId());
        }
        $this->airplaneRepository->remove($airplane);
    }
}
