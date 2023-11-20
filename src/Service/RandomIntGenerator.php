<?php

declare(strict_types=1);

namespace App\Service;

class RandomIntGenerator
{
    public function getRandomInt(): int
    {
        $date = new \DateTimeImmutable('now');

        $int = $date->format('Hisv');

        return (int)$int;
    }
}
