<?php

declare(strict_types=1);

namespace App\UseCase\Customer\AddOptions;

use App\Entity\Baggage;

class Command
{
    public int $options;
    public Baggage $baggage;
}