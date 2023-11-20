<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\FlightRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Flight
 */
#[ORM\Entity(repositoryClass: FlightRepository::class)]
class Flight
{
    /**
     * @var int|null $id
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var string|null $pointOfDeparture
     */
    #[ORM\Column(name: 'point_of_departure', length: 100)]
    private ?string $pointOfDeparture = null;

    /**
     * @var string|null $arrivalPoint
     */
    #[ORM\Column(name: 'arrival_point', length: 100)]
    private ?string $arrivalPoint = null;

    /**
     * @var Airplane|null $airplaneId
     */
    #[ORM\ManyToOne(targetEntity: Airplane::class, inversedBy: 'flights')]
    #[ORM\JoinColumn(name: 'airplane_id', referencedColumnName: 'id')]
    private ?Airplane $airplane = null;

    /**
     * @var Collection|null $tickets
     */
    #[ORM\OneToMany(mappedBy: 'flight', targetEntity: Ticket::class)]
    private ?Collection $tickets = null;

    /**
     * @var Collection|null $soldTickets
     */
    #[ORM\OneToMany(mappedBy: 'flight', targetEntity: SoldTicket::class)]
    private ?Collection $soldTickets = null;

    /**
     * @var \DateTimeImmutable|null $departureTime
     */
    #[ORM\Column(name: 'departure_time')]
    private \DateTimeImmutable|null $departureTime = null;

    /**
     * @var \DateTimeImmutable|null $arrivalTime
     */
    #[ORM\Column(name: 'arrival_time')]
    private \DateTimeImmutable|null $arrivalTime = null;

    public function __construct(
        string $pointOfDeparture,
        string $arrivalPoint,
        Airplane $airplane,
        \DateTimeImmutable $departureTime,
        \DateTimeImmutable $arrivalTime
    )
    {
        $this->pointOfDeparture = $pointOfDeparture;
        $this->arrivalPoint = $arrivalPoint;
        $this->airplane = $airplane;
        $this->departureTime = $departureTime;
        $this->arrivalTime = $arrivalTime;
        $this->tickets = new ArrayCollection();
        $this->soldTickets = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getPointOfDeparture(): string
    {
        return $this->pointOfDeparture;
    }

    /**
     * @param string $pointOfDeparture
     */
    public function setPointOfDeparture(string $pointOfDeparture): void
    {
        $this->pointOfDeparture = $pointOfDeparture;
    }

    /**
     * @return string
     */
    public function getArrivalPoint(): string
    {
        return $this->arrivalPoint;
    }

    /**
     * @param string $arrivalPoint
     */
    public function setArrivalPoint(string $arrivalPoint): void
    {
        $this->arrivalPoint = $arrivalPoint;
    }

    /**
     * @return Airplane
     */
    public function getAirplane(): Airplane
    {
        return $this->airplane;
    }

    /**
     * @param Airplane $airplane
     */
    public function setAirplane(Airplane $airplane): void
    {
        $this->airplane = $airplane;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDepartureTime(): \DateTimeImmutable
    {
        return $this->departureTime;
    }

    /**
     * @return string
     */
    public function getDepartureTimeString(): string 
    {
        return $this->departureTime->format('Y-m-d');
    }

    /**
     * @param \DateTimeImmutable $departureTime
     */
    public function setDepartureTime(\DateTimeImmutable $departureTime): void
    {
        $this->departureTime = $departureTime;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getArrivalTime(): \DateTimeImmutable
    {
        return $this->arrivalTime;
    }

    /**
     * @return string
     */
    public function getArrivalTimeString(): string
    {
        return $this->arrivalTime->format('Y-m-d h:m');
    }

    /**
     * @param \DateTimeImmutable $arrivalTime
     */
    public function setArrivalTime(\DateTimeImmutable $arrivalTime): void
    {
        $this->arrivalTime = $arrivalTime;
    }

    /**
     * @return Collection
     */
    public function getTickets(): Collection
    {
        return $this->tickets;        
    }

    /**
     * @return Collection
     */
    public function getSoldTickets(): Collection
    {
        return $this->soldTickets;        
    }
}
