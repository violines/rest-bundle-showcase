<?php

declare(strict_types=1);

namespace App\View;

use App\Entity\User;
use TerryApiBundle\Annotation\HTTPApi;

/**
 * @HTTPApi
 */
final class Profile
{
    private string $email;

    private array $roles;

    private ?string $key;

    public function __construct(
        string $email,
        array $roles,
        ?string $key = null
    ) {
        $this->email = $email;
        $this->roles = $roles;
        $this->key = $key;
    }

    public static function fromEntity(User $user): self
    {
        return new self($user->getEmail(), $user->getRoles(), $user->getKey());
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getKey(): ?string
    {
        return $this->key;
    }
}
