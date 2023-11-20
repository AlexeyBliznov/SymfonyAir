<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

/**
 * Class BaggageType
 * @extends StringType
 */
class BaggageType extends StringType
{
    public const NAME = 'user_baggage_type';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof Baggage ? $value->getName() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Baggage
    {
        return !empty($value) ? new Baggage($value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
