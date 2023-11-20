<?php

declare(strict_types=1);

namespace App\Entity;

use Webmozart\Assert\Assert;

/**
 * Class Seat
 */
class Seat
{
    /**
     * @var string STANDART standart seat
     */
    private const STANDART = 'STANDART';

    /**
     * @var string WINDOW window seat
     */
    private const WINDOW = 'WINDOW';

    /**
     * @var string EMERGENCY emergency seat
     */
    private const EMERGENCY = 'EMERGENCY';

    private string $name;

    public function __construct(string $name)
    {
        Assert::oneOf($name, [
            self::STANDART,
            self::WINDOW,
            self::EMERGENCY
        ]);

        $this->name = $name;
    }

    /**
     * @return self
     */
    public static function standart(): self
    {
        return new self(self::STANDART);
    }

    /**
     * @return self
     */
    public static function window(): self
    {
        return new self(self::WINDOW);
    }

    /**
     * @return self
     */
    public static function emergency(): self
    {
        return new self(self::EMERGENCY);
    }

    /**
     * @return bool
     */
    public function isStandart(): bool
    {
        return $this->name === self::STANDART;
    }

    /**
     * @return bool
     */
    public function isWindow(): bool
    {
        return $this->name === self::WINDOW;
    }

    /**
     * @return bool
     */
    public function isEmergency(): bool
    {
        return $this->name === self::EMERGENCY;
    }

    /**
     * @param Seat $seat
     * @return bool
     */
    public function isEqual(self $seat): bool
    {
        return $this->getName() === $seat->getName();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
