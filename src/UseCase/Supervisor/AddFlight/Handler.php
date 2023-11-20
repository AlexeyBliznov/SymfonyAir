<?php

declare(strict_types=1);

namespace App\UseCase\Supervisor\AddFlight;

use App\Entity\Flight;
use App\Service\TicketsGenerator;
use Doctrine\ORM\EntityManagerInterface;

class Handler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TicketsGenerator $ticketsGenerator
    )
    {
        $this->entityManager = $entityManager;
        $this->ticketsGenerator = $ticketsGenerator;
    }

    public function handle(Command $command): void
    {
        $flight = new Flight($command->from, $command->to, $command->airplane, $command->departureTime, $command->arrivalTime);
        $this->entityManager->persist($flight);
        $this->ticketsGenerator->getTickets($flight, $command->price);
        $this->entityManager->flush();
    }
}
