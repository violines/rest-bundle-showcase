<?php

declare(strict_types=1);

namespace App\User\View;

use App\User\Entity\User;
use Symfony\Component\Serializer;
use TerryApiBundle\Annotation\HTTPApi;

/**
 * @HTTPApi
 */
final class ProfileView
{
    /**
     * @Serializer\Annotation\SerializedName("user_id")
     */
    private int $userId;

    private string $email;

    private ?string $key;

    public function __construct(
        int $userId,
        string $email,
        ?string $key = null
    ) {
        $this->userId = $userId;
        $this->email = $email;
        $this->key = $key;
    }

    public static function fromEntity(User $user): self
    {
        return new self($user->getId(), $user->getEmail(), $user->getKey());
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getKey(): ?string
    {
        return $this->key;
    }
}
