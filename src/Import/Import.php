<?php

declare(strict_types=1);

namespace App\Import;

use App\Import\Model\Candy;
use App\Import\Model\Review;
use App\Import\Repository\CandyInterface;
use App\Import\Repository\ReviewInterface;

class Import
{
    private CandyInterface $candyRepository;

    private ReviewInterface $reviewRepository;

    public function __construct(
        CandyInterface $candyRepository,
        ReviewInterface $reviewRepository
    ) {
        $this->candyRepository = $candyRepository;
        $this->reviewRepository = $reviewRepository;
    }

    /**
     * @param Candy[] $candies
     */
    public function candies(array $candies): void
    {
        $this->candyRepository->saveMany($candies);
    }

    /**
     * @param Review[] $candies
     */
    public function reviews(array $reviews): void
    {
        $this->reviewRepository->saveMany($reviews);
    }
}
