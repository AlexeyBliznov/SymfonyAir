<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TicketRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TicketRepository::class)]
class Ticket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::INTEGER, unique: true)]
    private ?int $ticketNumber = null;

    #[ORM\ManyToOne(targetEntity: Flight::class, inversedBy: 'tickets')]
    #[ORM\JoinColumn(name: 'flight_id', referencedColumnName: 'id')]
    private ?Flight $flight = null;

    #[ORM\Column(type: 'user_seat_type')]
    private ?Seat $seatType = null;

    #[ORM\Column(length: 5)]
    private ?string $seatNumber = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $price = null;

    public function __construct(
        int $ticketNumber,
        Flight $flight,
        Seat $seatType,
        string $seatNumber,
        int $price
    )
    {
        $this->ticketNumber = $ticketNumber;
        $this->flight = $flight;
        $this->seatType = $seatType;
        $this->seatNumber = $seatNumber;
        $this->price = $price;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTicketNumber(): ?int
    {
        return $this->ticketNumber;
    }

    public function getFlight(): Flight
    {
        return $this->flight;
    }

    public function getSeatType(): string
    {
        return $this->seatType->getName();    
    }

    public function getSeatTypeObj() : Seat
    {
        return $this->seatType;
    }

    public function getSeatNumber(): string
    {
        return $this->seatNumber;
    }

    public function getPrice(): int
    {
        return $this->price;    
    }
}
