<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Infrastructure\Exception\AuthorizationFailedException;
use App\Infrastructure\Exception\BadRequestException;
use App\Infrastructure\Security\Voter\ReviewUniqueVoter;
use App\Infrastructure\View\Ok;
use App\Domain\Review\Command\CreateReview;
use App\Domain\User\Entity\User;
use SimpleBus\SymfonyBridge\Bus\CommandBus;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class ReviewController
{
    private CommandBus $commandBus;

    private Security $security;

    public function __construct(CommandBus $commandBus, Security $security)
    {
        $this->commandBus = $commandBus;
        $this->security = $security;
    }

    /**
     * @param User $user
     */
    #[Route('/{_locale}/review/create', methods: ['POST'], name: 'create_review', requirements: ['_locale' => 'en|de'])]
    public function createReview(CreateReview $createReview, UserInterface $user): Ok
    {
        if (!$this->security->isGranted(ReviewUniqueVoter::NAME, $createReview)) {
            throw AuthorizationFailedException::entryNotAllowed();
        }

        $createReview->userId = $user->getId();

        try {
            $this->commandBus->handle($createReview);
        } catch (\Throwable $e) {
            throw BadRequestException::reviewExists();
        }

        return Ok::create();
    }
}
