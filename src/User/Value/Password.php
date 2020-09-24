<?php

declare(strict_types=1);

namespace App\User\Value;

use Webmozart\Assert\Assert;

final class Password
{
    private string $password;

    private function __construct(string $password)
    {
        $this->password = $password;
    }

    public static function fromString(string $password)
    {
        Assert::stringNotEmpty($password);

        return new self($password);
    }

    public function toString(): string
    {
        return $this->password;
    }
}
