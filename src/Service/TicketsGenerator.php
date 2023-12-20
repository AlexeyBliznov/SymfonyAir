<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Flight;
use App\Entity\Seat;
use App\Entity\Ticket;
use Doctrine\ORM\EntityManagerInterface;

class TicketsGenerator
{
    public function __construct(
        public RandomIntGenerator $intGenerator,
        private EntityManagerInterface $entityManager)
    {
        $this->intGenerator = $intGenerator;
        $this->entityManager = $entityManager;       
    }

    public function getTickets(Flight $flight, int $price): void
    {
        $letters = ['A', 'B', 'C', 'D', 'E', 'F'];
        for($i = 1; $i <= 21; $i++) {
            foreach($letters as $letter) {
                if ($i === 10) {
                    $seatType = new Seat('EMERGENCY');
                } elseif ($letter === 'A' || $letter === 'F') {
                    $seatType = new Seat('WINDOW');
                } else {
                    $seatType = new Seat('STANDART');
                }

                $ticket = new Ticket($this->intGenerator->getRandomInt(), $flight, $seatType, $i . $letter, $price);
                $this->entityManager->persist($ticket);
                $this->entityManager->flush();
            }
        }
    }
}
