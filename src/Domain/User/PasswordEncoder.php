<?php

namespace App\Domain\User;

use App\Domain\User\User;

interface PasswordEncoder
{
    public function encode(User $user, string $password): string;
}
