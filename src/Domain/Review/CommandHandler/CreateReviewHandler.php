<?php

declare(strict_types=1);

namespace App\Domain\Review\CommandHandler;

use App\Domain\Review\Command\CreateReview;
use App\Domain\Review\Review;
use App\Domain\Review\Repository\ReviewRepository;

class CreateReviewHandler
{
    private ReviewRepository $reviewRepository;

    public function __construct(ReviewRepository $reviewRepository)
    {
        $this->reviewRepository = $reviewRepository;
    }

    public function __invoke(CreateReview $createReview): void
    {
        $nextId = $this->reviewRepository->nextId();

        $this->reviewRepository->saveReview(Review::fromCreate($nextId, $createReview));
    }
}
