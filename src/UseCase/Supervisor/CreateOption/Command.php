<?php

declare(strict_types=1);

namespace App\UseCase\Supervisor\CreateOption;

class Command
{
    public string $name;
    public string $description;
    public int $price;
}
