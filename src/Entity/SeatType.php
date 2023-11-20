<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class SeatType extends StringType
{
    public const NAME = 'user_seat_type';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof Seat ? $value->getName() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Seat
    {
        return !empty($value) ? new Seat($value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
