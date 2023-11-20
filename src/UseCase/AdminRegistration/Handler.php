<?php

declare(strict_types=1);

namespace App\UseCase\AdminRegistration;

use App\Entity\Admin;
use App\Repository\AdminRepository;
use App\Service\PasswordHasher;
use Doctrine\ORM\EntityManagerInterface;

class Handler
{
    public function __construct(
        private PasswordHasher $hasher,
        private EntityManagerInterface $entityManager,
        private AdminRepository $repository
    )
    {
        $this->hasher = $hasher;
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }

    public function handle(Command $command): void
    {
        if($this->repository->hasByEmail($command->email)) {
            throw new \DomainException('User already exists');
        }

        $user = new Admin(
            $command->email,
            $this->hasher->hash($command->password),
            $command->role
        );
        if ($command->name) {
            $user->setName($command->name);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
