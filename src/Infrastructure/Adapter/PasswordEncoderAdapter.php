<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapter;

use App\Domain\User\User;
use App\Domain\User\PasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordEncoderAdapter implements PasswordEncoder
{
    public UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function encode(User $user, string $password): string
    {
        return $this->passwordEncoder->encodePassword($user, $password);
    }
}
