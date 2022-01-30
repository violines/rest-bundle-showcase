<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapter;

use App\Domain\User\User;
use App\Domain\User\PasswordEncoder;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordEncoderAdapter implements PasswordEncoder
{
    public UserPasswordHasherInterface $passwordEncoder;

    public function __construct(UserPasswordHasherInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function encode(User $user, string $password): string
    {
        return $this->passwordEncoder->hashPassword($user, $password);
    }
}
