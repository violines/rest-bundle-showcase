<?php

declare(strict_types=1);

namespace App\Domain\Product\Repository;

use App\Domain\Product\Value\Language;
use App\Domain\Review\Value\Rating;

final class ProductListCriteria
{
    private const PAGE_SIZE = 10;

    private Language $language;

    private Rating $minRating;

    private Rating $maxRating;

    private int $page = 1;

    private function __construct(Language $language, Rating $minRating, Rating $maxRating)
    {
        $this->language = $language;
        $this->minRating = $minRating;
        $this->maxRating = $maxRating;
    }

    public static function withDefaults(Language $language): static
    {
        $minRating = Rating::fromInt(1);
        $maxRating = Rating::fromInt(5);

        return new self($language, $minRating, $maxRating);
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

    public function forPage(int $page): static
    {
        $this->page = $page;

        return $this;
    }

    public function getLanguage(): Language
    {
        return $this->language;
    }

    public function getMinRating(): Rating
    {
        return $this->minRating;
    }

    public function getMaxRating(): Rating
    {
        return $this->maxRating;
    }

    public function offset(): int
    {
        return ($this->page - 1) * self::PAGE_SIZE;
    }

    public function limit(): int
    {
        return self::PAGE_SIZE;
    }
}
