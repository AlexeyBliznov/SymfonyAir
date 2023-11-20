<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\SoldTicketRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;

/**
 * Class SoldTicket
 */
#[ORM\Entity(repositoryClass: SoldTicketRepository::class)]
class SoldTicket
{
    /**
     * @var int|null $id
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var int|null $ticketNumber
     */
    #[ORM\Column(type: Types::INTEGER, unique: true)]
    private ?int $ticketNumber = null;

    /**
     * @var Flight|null $flight 
     */
    #[ORM\ManyToOne(targetEntity: Flight::class, inversedBy: 'soldTickets')]
    #[ORM\JoinColumn(name: 'flight_id', referencedColumnName: 'id')]
    private ?Flight $flight = null;

    /**
     * @var User|null $user
     */
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'tickets')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private ?User $user = null;

    /**
     * @var Seat|null $seatType
     */
    #[ORM\Column(type: 'user_seat_type', length: 255)]
    private ?Seat $seatType = null;

    /**
     * @var string|null $seatNumber
     */
    #[ORM\Column(length: 5)]
    private ?string $seatNumber = null;

    /**
     * @var Baggage|null $baggage
     */
    #[ORM\Column(type: 'user_baggage_type', length: 255)]
    private ?Baggage $baggage = null;

    /**
     * @var Collection|null $options
     */
    #[ORM\ManyToMany(targetEntity: Option::class, inversedBy: 'tickets')]
    private ?Collection $options = null;

    /**
     * @var string|null $discount
     */
    #[ORM\Column(nullable: true)]
    private ?string $discount = null;

    /**
     * @var bool $boardingConfirm
     */
    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $boardingConfirm = false;

    /**
     * @var bool $checkIn
     */
    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $checkIn = false;

    public function __construct(
        int $ticketNumber,
        Flight $flight,
        User $user,
        Seat $seatType,
        string $seatNumber,
        Baggage $baggage,
        array $options = null
    )
    {
        $this->ticketNumber = $ticketNumber;
        $this->flight = $flight;
        $this->user = $user;
        $this->seatType = $seatType;
        $this->seatNumber = $seatNumber;
        $this->baggage = $baggage;
        $this->options = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getTicketNumber(): ?int
    {
        return $this->ticketNumber;
    }

    /**
     * @param int $ticketNumber
     */
    public function setTicketNumber(int $ticketNumber): void
    {
        $this->ticketNumber = $ticketNumber;
    }

    /**
     * @return Flight|null
     */
    public function getFlight(): ?Flight
    {
        return $this->flight;
    }

    /**
     * @param Flight $flight
     * @return Flight|null
     */
    public function setFlight(Flight $flight): self
    {
        $this->flight = $flight;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return User|null
     */
    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Seat|null
     */
    public function getSeatType(): ?Seat
    {
        return $this->seatType;
    }

    /**
     * @param Seat $seatType
     * @return Seat|null
     */
    public function setSeatType(Seat $seatType): self
    {
        $this->seatType = $seatType;

        return $this;
    }

    /**
     * @return string
     */
    public function getSeatNumber(): string
    {
        return $this->seatNumber;   
    }

    /**
     * @return Baggage|null
     */
    public function getBaggage(): ?Baggage
    {
        return $this->baggage;
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
