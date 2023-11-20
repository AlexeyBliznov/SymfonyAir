<?php

declare(strict_types=1);

namespace App\UseCase\AdminRegistration;

use App\Entity\Role;

class Command
{
    public string $email;

    public string $name;

    public string $password;

    public Role $role;
}