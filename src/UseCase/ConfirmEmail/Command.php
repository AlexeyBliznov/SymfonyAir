<?php

declare(strict_types=1);

namespace App\UseCase\ConfirmEmail;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    #[Assert\NotBlank]
    public string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }
}
