<?php

namespace App\Domain\User;

use App\Domain\User\Entity\User;

interface PasswordEncoder
{
    public function encode(User $user, string $password): string;
}
