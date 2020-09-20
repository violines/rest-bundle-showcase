<?php

declare(strict_types=1);

namespace App\User\View;

use App\User\Entity\User as UserEntity;
use TerryApiBundle\Annotation\HTTPApi;

/**
 * @HTTPApi
 */
final class UserView
{
    private int $id;

    private string $email;

    private array $roles;

    private ?string $key;

    public function __construct(
        int $id,
        string $email,
        array $roles,
        ?string $key = null
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->roles = $roles;
        $this->key = $key;
    }

    public static function fromEntity(UserEntity $user): self
    {
        return new self($user->getId(), $user->getEmail(), $user->getRoles(), $user->getKey());
    }

    public function getId(): int
    {
        return $this->id;
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
