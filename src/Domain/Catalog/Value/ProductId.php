<?php

declare(strict_types=1);

namespace App\Domain\Catalog\Value;

use Webmozart\Assert\Assert;

final class ProductId
{
    private int $productId;

    private function __construct(int $productId)
    {
        $this->productId = $productId;
    }

    public static function fromInt(int $productId)
    {
        Assert::greaterThanEq($productId, 1, 'Value must be positive and not 0');

        return new self($productId);
    }

    public function toInt(): int
    {
        return $this->productId;
    }
}
