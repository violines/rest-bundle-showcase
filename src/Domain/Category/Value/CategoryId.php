<?php

declare(strict_types=1);

namespace App\Domain\Category\Value;

use Webmozart\Assert\Assert;

final class CategoryId
{
    private int $categoryId;

    private function __construct(int $categoryId)
    {
        $this->categoryId = $categoryId;
    }

    public static function fromInt(int $categoryId)
    {
        Assert::greaterThanEq($categoryId, 1, 'Value must be positive and not 0');

        return new self($categoryId);
    }

    public function toInt(): int
    {
        return $this->categoryId;
    }
}
