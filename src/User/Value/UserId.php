<?php

declare(strict_types=1);

namespace App\User\Value;

use Webmozart\Assert\Assert;

final class UserId
{
    private int $userId;

    private function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    public static function fromInt(int $userId)
    {
        Assert::greaterThanEq($userId, 1, 'Value must be positive and not 0');

        return new self($userId);
    }

    public function toInt(): int
    {
        return $this->userId;
    }
}
