<?php

namespace App\Review\Repository;

use App\Product\Value\ProductId;
use App\Review\Entity\Review;
use App\Review\Value\ReviewId;
use App\User\Value\UserId;

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
