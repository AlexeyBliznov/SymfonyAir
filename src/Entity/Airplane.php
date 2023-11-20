<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\AirplaneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Airplane
 */
#[ORM\Entity(repositoryClass: AirplaneRepository::class)]
class Airplane
{
    /**
     * @var int|null $id
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var string|null $model
     */
    #[ORM\Column(length: 50)]
    private ?string $model = null;

    /**
     * @var \DateTimeImmutable|null $productionYear
     */
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, name: 'production_year')]
    private ?\DateTimeImmutable $productionYear = null;

    /**
     * @var \DateInterval|null $maintenanceSchedule
     */
    #[ORM\Column(type: Types::DATEINTERVAL, name: 'maintenance_schedule')]
    private ?\DateInterval $maintenanceSchedule = null;

    /**
     * @var \DateTimeImmutable|null $nextMaintenance
     */
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, name: 'next_maintenance')]
    private ?\DateTimeImmutable $nextMaintenance = null;

    /**
     * @var string|null $type
     */
    #[ORM\Column(length: 50)]
    private ?string $type = null;

    /**
     * @var Collection|null $flights
     */
    #[ORM\OneToMany(mappedBy: 'airplane', targetEntity: Flight::class)]
    private ?Collection $flights = null;

    public function __construct(
        string $model,
        \DateTimeImmutable $productionYear,
        \DateInterval $maintenanceSchedule,
        \DateTimeImmutable $nextMaintenance,
        string $type
    )
    {
        $this->model = $model;
        $this->productionYear = $productionYear;
        $this->maintenanceSchedule = $maintenanceSchedule;
        $this->nextMaintenance = $nextMaintenance;
        $this->type = $type;
        $this->flights = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getModel(): ?string
    {
        return $this->model;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getProductionYear(): ?\DateTimeImmutable
    {
        return $this->productionYear;
    }

    /**
     * @return \DateInterval|null
     */
    public function getMaintenanceSchedule(): ?\DateInterval
    {
        return $this->maintenanceSchedule;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getNextMaintenance(): ?\DateTimeImmutable
    {
        return $this->nextMaintenance;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @return Collection|null
     */
    public function getFlights(): ?Collection
    {
        return $this->flights;
    }
}
