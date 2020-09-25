<?php

declare(strict_types=1);

namespace App\Product\Value;

use Webmozart\Assert\Assert;

final class Gtin
{
    private string $gtin;

    private function __construct(string $gtin)
    {
        $this->gtin = $gtin;
    }

    public static function fromString(string $gtin)
    {
        Assert::stringNotEmpty($gtin);

        return new self($gtin);
    }

    public function toString(): string
    {
        return $this->gtin;
    }
}
