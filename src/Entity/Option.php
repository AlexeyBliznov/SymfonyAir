<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Option
 */
#[ORM\Entity(repositoryClass: OptionRepository::class)]
class Option
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
     * @var string|null $description
     */
    #[ORM\Column]
    private ?string $description = null;

    /**
     * @var string|null $price
     */
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $price = null;

    /**
     * @var Collection|null $tickets
     */
    #[ORM\ManyToMany(targetEntity: Ticket::class, mappedBy: 'options')]
    private ?Collection $tickets = null;
    
    public function __construct(string $name, string $description, int $price)
    {
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->tickets = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return int|null
     */
    public function getPrice(): ?int
    {
        return $this->price;
    }

    /**
     * @return Collection|null
     */
    public function getTickets(): ?Collection
    {
        return $this->tickets;
    }
}
