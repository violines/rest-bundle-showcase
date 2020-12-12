<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Infrastructure\Exception\AuthorizationFailedException;
use App\Infrastructure\Exception\BadRequestException;
use App\Infrastructure\Security\Voter\ReviewUniqueVoter;
use App\Infrastructure\View\Ok;
use App\Review\Command\CreateReview;
use App\Review\ReviewService;
use App\User\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class ReviewController
{
    private ReviewService $reviewService;

    private Security $security;

    public function __construct(ReviewService $reviewService, Security $security)
    {
        $this->reviewService = $reviewService;
        $this->security = $security;
    }

    /**
     * @param User $user
     */
    #[Route('/{_locale}/review', methods: ['POST'], name:'frontend_create_review', requirements:['_locale' => 'en|de'])]
    public function createReview(CreateReview $createReview, UserInterface $user): Ok
    {
        if (!$this->security->isGranted(ReviewUniqueVoter::NAME, $createReview)) {
            throw AuthorizationFailedException::entryNotAllowed();
        }

        $createReview->userId = $user->getId();

        try {
            $this->reviewService->createReview($createReview);
        } catch (\Throwable $e) {
            throw BadRequestException::reviewExists();
        }

        return Ok::create();
    }
}
