<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\AdminRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class Admin
 * @implements UserInterface
 * @implements PasswordAuthenticatedUserInterface
 */
#[ORM\Entity(repositoryClass: AdminRepository::class)]
#[ORM\Table(name: '`admin`')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class Admin implements UserInterface, PasswordAuthenticatedUserInterface
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
    #[ORM\Column(length: 180, nullable: true)]
    private ?string $name = null;

    /**
     * @var string|null $email
     */
    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    /**
     * @var Role $role
     */
    #[ORM\Column(type:'user_user_role')]
    private Role $role;

    /**
     * @var string|null $password
     */
    #[ORM\Column]
    private ?string $password = null;

    public function __construct(string $email, string $password, Role $role)
    {
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }

    /**
     * Get user's identifier
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get user's email
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Set user's email
     *
     * @param string $email
     * @return $this
     */
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

    /**
     * Set user's name
     *
     * @param string $name
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Get user's name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @see UserInterface
     */
    public function getRole(): Role
    {
        return $this->role;
    }

    /**
     * Set user's role
     *
     * @param Role $role
     * @return void
     */
    public function setRole(Role $role): void
    {
        $this->role = $role;
    }

    /**
     * Change User's role
     *
     * @param Role $role
     * @return void
     */
    public function changeRole(Role $role): void
    {
        if ($this->role->isEqual($role)) {
            throw new \DomainException('Role is already same.');
        }
        $this->role = $role;
    }

    /**
     * Get roles
     *
     * @return array|string[]
     */
    public function getRoles(): array
    {
        return [$this->role->getName()];
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Set user's password
     *
     * @param string $password
     * @return $this
     */
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
