<?php

declare(strict_types=1);

namespace App\UseCase\Supervisor\EditFlight;

use App\Repository\FlightRepository;
use Doctrine\ORM\EntityManagerInterface;

readonly class Handler
{
    public function __construct(
        private FlightRepository $flightRepository,
        private EntityManagerInterface $entityManager
    ) 
    {
        $this->flightRepository = $flightRepository;
        $this->entityManager = $entityManager;
    }

    public function handle(int $id, Command $command): void
    {
        $flight = $this->flightRepository->find($id);
        $flight->setPoinOfDeparture($command->pointOfDeparture);
        $flight->setArrivalPoint($command->arrivalPoint);
        $flight->setPlaneId($command->airplane);
        $flight->setDepartureTime($command->departureTime);
        $flight->setArrivalTime($command->arrivalTime);

        $this->entityManager->persist($flight);
        $this->entityManager->flush();
    }
}
