<?php

namespace App\User;

use App\User\Entity\User;

interface PasswordEncoder
{
    public function encode(User $user, string $password): string;
}
