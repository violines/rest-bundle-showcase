<?php

declare(strict_types=1);

namespace App\View;

use TerryApiBundle\Annotation\HTTPApi;
use App\Entity\User as UserEntity;

/**
 * @HTTPApi
 */
class User
{
    private string $email;

    private ?string $key;

    private array $roles;

    public function __construct(string $email, array $roles, ?string $key = null)
    {
        $this->email = $email;
        $this->roles = $roles;
        $this->key = $key;
    }

    public static function fromEntity(UserEntity $user)
    {
        return new self($user->getEmail(), $user->getRoles(), $user->getKey());
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }
}
