<?php

declare(strict_types=1);

namespace App\UseCase\Supervisor\AddFlight;

use App\Entity\Airplane;

class Command
{
    public \DateTimeImmutable $departureTime;
    public \DateTimeImmutable $arrivalTime;
    public string $from;
    public string $to;
    public Airplane $airplane;
    public int $price;
}
