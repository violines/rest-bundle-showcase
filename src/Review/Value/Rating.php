<?php

declare(strict_types=1);

namespace App\Review\Value;

use Webmozart\Assert\Assert;

final class Rating
{
    private int $rating;

    private function __construct(int $rating)
    {
        $this->rating = $rating;
    }

    public static function fromInt(int $rating)
    {
        Assert::greaterThanEq($rating, 1, 'Value cannot be less than 1');
        Assert::lessThanEq($rating, 5, 'Value cannot be greater than 5');

        return new self($rating);
    }

    public function toInt(): int
    {
        return $this->rating;
    }
}
