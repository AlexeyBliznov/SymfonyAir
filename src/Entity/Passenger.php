<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\PassengerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Passenger
 */
#[ORM\Entity(repositoryClass: PassengerRepository::class)]
class Passenger
{
    /**
     * @var int|null $id
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var string|null $name
     */
    #[ORM\Column]
    private ?string $name = null;

    /**
     * @var string|null $email
     */
    #[ORM\Column(unique: true)]
    private ?string $email = null;

    /**
     * @var Collection|ArrayCollection|null $tickets
     */
    #[ORM\OneToMany(mappedBy: 'passenger', targetEntity: Ticket::class)]
    private ?Collection $tickets = null;

    /**
     * @var \DateTimeImmutable|null $expires
     */
    #[ORM\Column]
    private ?\DateTimeImmutable $expires = null;

    public function __construct(string $name, string $email)
    {
        $this->name = $name;
        $this->email = $email;
        $this->tickets = new ArrayCollection();
    }
}
