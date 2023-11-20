<?php

declare(strict_types=1);

namespace App\UseCase\Supervisor\RemoveEmployee;

use App\Entity\Admin;
use App\Repository\AdminRepository;

class Handler
{
    private AdminRepository $repository;

    public function __construct(AdminRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(int $id): void
    {
        $user = $this->repository->find($id);
        $this->repository->remove($user, true);
    }
}
