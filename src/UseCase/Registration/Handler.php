<?php

declare(strict_types=1);

namespace App\UseCase\Registration;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\MailSender;
use App\Service\PasswordHasher;
use App\Service\TokenGenerator;
use Doctrine\ORM\EntityManagerInterface;

class Handler
{
    private PasswordHasher $hasher;
    private TokenGenerator $generator;
    private MailSender $mailer;
    private UserRepository $repository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        PasswordHasher $hasher,
        TokenGenerator $generator,
        EntityManagerInterface $entityManager,
        MailSender $mailer,
        UserRepository $repository
    )
    {
        $this->hasher = $hasher;
        $this->generator = $generator;
        $this->mailer = $mailer;
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }

    public function handle(Command $command): void
    {
        if($this->repository->hasByEmail($command->email)) {
            throw new \DomainException('User already exists');
        }

        $user = new User();
        $user->create(
            $command->email,
            $this->hasher->hash($command->password),
            $token = $this->generator->generate()
        );

        if ($command->name) {
            $user->setName($command->name);
        }

        $this->entityManager->persist($user);
        $this->mailer->send($command->email, $token);
        $this->entityManager->flush();
    }
}