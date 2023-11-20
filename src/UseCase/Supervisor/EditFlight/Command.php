<?php

declare(strict_types=1);

namespace App\UseCase\Supervisor\EditFlight;

use App\Entity\Airplane;

class Command
{
    public string $pointOfDeparture;
    public string $arrivalPoint;
    public Airplane $airplane;
    public \DateTimeImmutable $departureTime;
    public \DateTimeImmutable $arrivalTime;
}
