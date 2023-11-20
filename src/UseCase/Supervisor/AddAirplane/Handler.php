<?php

declare(strict_types=1);

namespace App\UseCase\Supervisor\AddAirplane;

use App\Entity\Airplane;
use Doctrine\ORM\EntityManagerInterface;

class Handler
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function handle(Command $command): void
    {
        $flight = new Airplane($command->name, $command->productionYear, $command->maintenanceSchedule, $command->nextMaintenance, $command->type);
        $this->entityManager->persist($flight);
        $this->entityManager->flush();
    }
}
