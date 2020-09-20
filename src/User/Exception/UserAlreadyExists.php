<?php

declare(strict_types=1);

namespace App\User\Exception;

class UserAlreadyExists extends \LogicException implements \Throwable
{
    public static function email(string $email): self
    {
        return new self(sprintf('A User with email: %s does already exist.', $email));
    }
}
