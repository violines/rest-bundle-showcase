<?php

declare(strict_types=1);

namespace App\Review;

use App\Review\Command\CreateReview;
use App\Review\Entity\Review;
use App\Review\Repository\ReviewRepository;
use App\Review\Value\ReviewId;

class ReviewService
{
    private ReviewRepository $reviewRepository;

    public function __construct(ReviewRepository $reviewRepository)
    {
        $this->reviewRepository = $reviewRepository;
    }

    public function createReview(CreateReview $createReview): void
    {
        $nextId = ReviewId::new($this->reviewRepository->nextId());

        $this->reviewRepository->saveReview(Review::fromCreate($nextId, $createReview));
    }
}
