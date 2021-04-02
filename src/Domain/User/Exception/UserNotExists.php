<?php

declare(strict_types=1);

namespace App\Domain\User\Exception;

class UserNotExists extends \LogicException implements \Throwable
{
    public static function id(int $id): self
    {
        return new self(sprintf('User with id: %s does not exist.', $id));
    }
}
