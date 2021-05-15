<?php

namespace App\Domain\Review\Repository;

use App\Domain\Product\Value\Gtin;
use App\Domain\Review\Review;
use App\Domain\Review\Value\ReviewId;
use App\Domain\User\Value\UserId;

interface ReviewRepository
{
    /**
     * @return ReviewId
     */
    public function nextId(): ReviewId;

    /**
     * @param Review $review
     */
    public function save(Review $review): void;

    /**
     * @param Gtin $gtin
     * @param UserId $userId
     * @return bool
     */
    public function exists(Gtin $gtin, UserId $userId): bool;

    /**
     * @param ReviewId $
     * @return Review
     */
    public function find(ReviewId $reviewId): Review;
}
