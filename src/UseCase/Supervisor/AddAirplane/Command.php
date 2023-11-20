<?php

declare(strict_types=1);

namespace App\UseCase\Supervisor\AddAirplane;

class Command
{
    public string $name;
    public \DateTimeImmutable $productionYear;
    public \DateInterval $maintenanceSchedule;
    public \DateTimeImmutable $nextMaintenance;
    public string $type;
}
