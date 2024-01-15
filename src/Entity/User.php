<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column(type: 'user_user_status')]
    private Status $status;

    #[ORM\Column(type:'user_user_role')]
    private Role $role;

    #[ORM\Column(name: 'confirm_token', nullable: true)]
    private string|null $confirmToken;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Ticket::class)]
    private ?Collection $tickets = null;

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    public function __construct()
    {
        $this->status = Status::new();
    }

    public function create(string $email, string $password, string $confirmToken = null): void
    {
        $this->email = $email;
        $this->password = $password;
        $this->confirmToken = $confirmToken;
        $this->role = Role::user();
        $this->status = Status::wait();
    }

    public function confirmSignUp(): void
    {
        if ($this->status->isActive()) {
            throw new \DomainException('User is already confirmed');
        }

        $this->status = Status::active();
        $this->confirmToken = null;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getConfirmToken(): ?string
    {
        return $this->confirmToken;
    }

    /**
     * @see UserInterface
     */
    public function getRole(): Role
    {
        return $this->role;
    }

    public function setRole(Role $role): void
    {
        $this->role = $role;
    }

    public function changeRole(Role $role): void
    {
        if ($this->role->isEqual($role)) {
            throw new \DomainException('Role is already same.');
        }
        $this->role = $role;
    }

    public function getRoles(): array
    {
        return [$this->role->getName()];
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function getTickets(): Collection
    {
        return $this->tickets;   
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
