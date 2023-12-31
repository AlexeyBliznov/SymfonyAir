<?php

declare(strict_types=1);

namespace App\Service;

class PasswordHasher
{
    public function hash(string $value): string
    {
        $hash = password_hash($value, PASSWORD_ARGON2I);

        if($hash === false) {
            throw new \RuntimeException('Unable to generate hash');
        }

        return $hash;
    }
}
