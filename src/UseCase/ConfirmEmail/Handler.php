<?php

declare(strict_types=1);

namespace App\UseCase\ConfirmEmail;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class Handler
{
    private UserRepository $repository;
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager, UserRepository $repository)
    {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }

    public function handle(Command $command): void
    {
        if(!$user = $this->repository->findByConfirmToken($command->token)) {
            throw new \DomainException('Incorrect token');
        }

        $user->confirmSignUp();

        $this->entityManager->flush();
    }
}