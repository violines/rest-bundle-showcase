<?php

declare(strict_types=1);

namespace App\Domain\Product\Repository;

use App\Domain\Review\Value\Rating;

final class ProductViewCriteria
{
    private Rating $minRating;

    private Rating $maxRating;

    private function __construct(Rating $minRating, Rating $maxRating)
    {
        $this->minRating = $minRating;
        $this->maxRating = $maxRating;
    }

    public static function withDefaults(): static
    {
        $minRating = Rating::fromInt(1);
        $maxRating = Rating::fromInt(5);

        return new self($minRating, $maxRating);
    }

    public function andMinRating(Rating $rating): static
    {
        $this->minRating = $rating;

        return $this;
    }

    public function andMaxRating(Rating $rating): static
    {
        $this->maxRating = $rating;

        return $this;
    }

    public function minRatingAsInt(): int
    {
        return $this->minRating->toInt();
    }

    public function maxRatingAsInt(): int
    {
        return $this->maxRating->toInt();
    }
}
