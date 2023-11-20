<?php

declare(strict_types=1);

namespace App\UseCase\Supervisor\AddEmployee;

use App\Entity\Role;

class Command
{
    public string $name;
    public string $email;
    public Role $role;
}
