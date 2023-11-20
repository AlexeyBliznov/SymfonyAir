<?php

declare(strict_types=1);

namespace App\Entity;

use Webmozart\Assert\Assert;

/**
 * Class Role
 */
class Role
{
    private const USER = 'ROLE_USER';
    private const GATE_MANAGER = 'ROLE_GATE_MANAGER';
    private const CHECK_IN_MANAGER = 'ROLE_CHECK_IN_MANAGER';
    private const SUPERVISOR = 'ROLE_SUPERVISOR';

    /**
     * @var string $name
     */
    private string $name;

    public function __construct(string $name)
    {
        Assert::oneOf($name, [
            self::USER,
            self::GATE_MANAGER,
            self::CHECK_IN_MANAGER,
            self::SUPERVISOR
        ]);

        $this->name = $name;
    }

    /**
     * @return self
     */
    public static function user(): self
    {
        return new self(self::USER);
    }

    /**
     * @return self
     */
    public static function gateManager(): self
    {
        return new self(self::GATE_MANAGER);
    }

    /**
     * @return self
     */
    public static function checkInManager(): self
    {
        return new self(self::CHECK_IN_MANAGER);
    }

    /**
     * @return self
     */
    public static function supervisor(): self
    {
        return new self(self::SUPERVISOR);
    }

    /**
     * @return bool
     */
    public function isUser(): bool
    {
        return $this->name === self::USER;
    }

    /**
     * @return bool
     */
    public function isGateManager(): bool
    {
        return $this->name === self::GATE_MANAGER;
    }

    /**
     * @return bool
     */
    public function isCheckInManager(): bool
    {
        return $this->name === self::CHECK_IN_MANAGER;
    }

    /**
     * @return bool
     */
    public function isSupervisor(): bool
    {
        return $this->name === self::SUPERVISOR;
    }

    /**
     * @param Role $role
     * @return bool
     */
    public function isEqual(self $role): bool
    {
        return $this->getName() === $role->getName();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
