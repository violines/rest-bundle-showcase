<?php

declare(strict_types=1);

namespace App\Domain\Review;

use App\Domain\Review\Command\CreateReview;
use App\Domain\Review\Entity\Review;
use App\Domain\Review\Repository\ReviewRepository;

class ReviewService
{
    private ReviewRepository $reviewRepository;

    public function __construct(ReviewRepository $reviewRepository)
    {
        $this->reviewRepository = $reviewRepository;
    }

    public function createReview(CreateReview $createReview): void
    {
        $nextId = $this->reviewRepository->nextId();

        $this->reviewRepository->saveReview(Review::fromCreate($nextId, $createReview));
    }
}
