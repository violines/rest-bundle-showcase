<?php

declare(strict_types=1);

namespace App\Domain\Review\CommandHandler;

use App\Domain\Review\Command\CreateReview;
use App\Domain\Review\Review;
use App\Domain\Review\Repository\ReviewRepository;

class CreateReviewHandler
{
    public function __construct(private ReviewRepository $reviewRepository)
    {
    }

    public function __invoke(CreateReview $createReview): void
    {
        $nextId = $this->reviewRepository->nextId();

        $this->reviewRepository->save(Review::fromCreate($nextId, $createReview));
    }
}
