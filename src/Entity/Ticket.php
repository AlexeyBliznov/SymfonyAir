<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TicketRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Stringable;

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

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $book = false;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'tickets')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private ?User $user = null;

    #[ORM\Column(nullable: true)]
    private ?string $passengerName = null;

    #[ORM\Column(type: 'user_baggage_type', length: 255, nullable: true)]
    private ?Baggage $baggage = null;

    #[ORM\ManyToMany(targetEntity: Option::class, inversedBy: 'tickets')]
    private ?Collection $options = null;

    #[ORM\Column(nullable: true)]
    private ?string $discount = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $boardingConfirm = false;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $checkIn = false;

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
        $this->options = new ArrayCollection();
    }

    public function bookTicket(User $user, Baggage $baggage): void
    {
        $this->book = true;
        $this->user = $user;
        $this->baggage = $baggage;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getBook(): bool
    {
        return $this->book;
    }

    public function setPassengerName(string $passengerName): void
    {
        $this->passengerName = $passengerName;
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

    /**
     * @return Baggage|null
     */
    public function getBaggage(): ?string
    {
        return $this->baggage->getName();
    }

    /**
     * @param Baggage $baggage
     * @return Baggage|null
     */
    public function setBaggage(Baggage $baggage): self
    {
        $this->baggage = $baggage;

        return $this;
    }

    /**
     * @return Collection|null
     */
    public function getOptions(): Collection
    {
        return $this->options;
    }

    /**
     * @param Option $option
     */
    public function addOption(Option $option): void
    {
        if (!$this->options->contains($option)) {
            $this->options[] = $option;
        }   
    }

    /**
     * @return void
     */
    public function checkIn(): void
    {
        $this->checkIn = true;        
    }

    /**
     * @return void
     */
    public function confirmBoarding(): void
    {
        $this->boardingConfirm = true;
    }
}
