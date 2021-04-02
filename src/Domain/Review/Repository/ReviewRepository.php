<?php

namespace App\Domain\Review\Repository;

use App\Domain\Product\Value\ProductId;
use App\Domain\Review\Entity\Review;
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
    public function saveReview(Review $review): void;

    /**
     * @param ProductId $productId
     * @param UserId $userId
     * @return bool
     */
    public function reviewExists(ProductId $productId, UserId $userId): bool;

    /**
     * @param ReviewId $
     * @return Review
     */
    public function findReview(ReviewId $reviewId): Review;

    /**
     * @return Review[]
     */
    public function findReviews(): array;
}
