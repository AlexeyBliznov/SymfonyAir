<?php

declare(strict_types=1);

namespace App\UseCase\Supervisor\AddEmployee;

use App\Entity\Admin;
use App\Repository\AdminRepository;
use App\Service\EmployeeMailSender;
use App\Service\PasswordHasher;
use App\Service\RandomIntGenerator;
use Doctrine\ORM\EntityManagerInterface;

class Handler
{
    private PasswordHasher $hasher;
    private EmployeeMailSender $mailer;
    private AdminRepository $repository;
    private EntityManagerInterface $entityManager;

    private RandomIntGenerator $randomIntGenerator;

    public function __construct(
        PasswordHasher $hasher,
        EntityManagerInterface $entityManager,
        EmployeeMailSender $mailer,
        AdminRepository $repository,
        RandomIntGenerator $randomIntGenerator
    )
    {
        $this->hasher = $hasher;
        $this->mailer = $mailer;
        $this->entityManager = $entityManager;
        $this->repository = $repository;
        $this->randomIntGenerator = $randomIntGenerator;
    }

    public function handle(Command $command): void
    {
        if($this->repository->hasByEmail($command->email)) {
            throw new \DomainException('User already exists');
        }

        $password = $this->randomIntGenerator->getRandomInt();
        $user = new Admin(
            $command->email,
            $this->hasher->hash((string)$password),
            $command->role
        );
        
        if ($command->name) {
            $user->setName($command->name);
        }

        $this->entityManager->persist($user);
        $this->mailer->send($command->email, $command->name, $command->role->getName(), (string)$password);
        $this->entityManager->flush();
    }
}
