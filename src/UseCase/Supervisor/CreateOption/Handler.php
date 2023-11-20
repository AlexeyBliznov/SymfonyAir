<?php

declare(strict_types=1);

namespace App\UseCase\Supervisor\CreateOption;

use App\Entity\Option;
use Doctrine\ORM\EntityManagerInterface;

class Handler
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function handle(Command $command): void
    {
        $option = new Option($command->name, $command->description, $command->price);
        $this->entityManager->persist($option);
        $this->entityManager->flush();
    }
}
