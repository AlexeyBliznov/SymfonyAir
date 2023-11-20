<?php

declare(strict_types=1);

namespace App\UseCase\Supervisor\RemoveFlight;

use App\Repository\FlightRepository;
use App\Repository\TicketRepository;

readonly class Handler
{
    public function __construct(private FlightRepository $flightRepository, private TicketRepository $ticketRepository)
    {
        $this->flightRepository = $flightRepository;
        $this->ticketRepository = $ticketRepository;
    }

    public function handle(int $id): void
    {
        $flight = $this->flightRepository->find($id);
        $this->ticketRepository->removeByFlight($flight);
        $this->flightRepository->remove($flight, true);
    }
}
