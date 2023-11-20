<?php

declare(strict_types=1);

namespace App\Service;

use Ramsey\Uuid\Uuid;

class TokenGenerator
{
    public function generate(): string
    {
        return Uuid::uuid4()->toString();
    }
}
