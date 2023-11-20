<?php

declare(strict_types=1);

namespace App\Entity;

use Webmozart\Assert\Assert;

/**
 * Class Baggage
 */
class Baggage
{
    /**
     * @var string HAND_BAGGAGE you can take only hang luggage 
     */
    private const HAND_BAGGAGE = 'HAND BAGGAGE';

    /**
     * @var string BAGGAGE you can take standart luggage
     */
    private const BAGGAGE = 'BAGGAGE';

    /**
     * @var string PETS you can take pets and hang luggage
     */
    private const PETS = 'PETS + HAND BAGGAGE';

    /**
     * @var string PETS_BAGGAGE you can take pets and standart luggage
     */
    private const PETS_BAGGAGE = 'PETS + BAGGAGE';

    private string $name;

    public function __construct(string $name)
    {
        Assert::oneOf($name, [
            self::HAND_BAGGAGE,
            self::BAGGAGE,
            self::PETS,
            self::PETS_BAGGAGE
        ]);

        $this->name = $name;
    }

    public static function handBaggage(): self
    {
        return new self(self::HAND_BAGGAGE);
    }

    public static function baggage(): self
    {
        return new self(self::BAGGAGE);
    }

    public static function pets(): self
    {
        return new self(self::PETS);
    }

    public function petsBaggage(): self 
    {
        return new self(self::PETS_BAGGAGE);
    }

    public function isHandBaggage(): bool
    {
        return $this->name === self::HAND_BAGGAGE;
    }

    public function isBaggage(): bool
    {
        return $this->name === self::BAGGAGE;
    }

    public function isPets(): bool
    {
        return $this->name === self::PETS;
    }

    public function isPetsBaggage(): bool
    {
        return $this->name === self::PETS_BAGGAGE;
    }

    public function isEqual(self $baggage): bool
    {
        return $this->getName() === $baggage->getName();
    }

    public function getName(): string
    {
        return $this->name;
    }
}
