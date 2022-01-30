<?php

declare(strict_types=1);

namespace App\Infrastructure\Security\Voter;

use App\Domain\Catalog\Value\Gtin;
use App\Domain\Review\Command\CreateReview;
use App\Domain\Review\Repository\ReviewRepository;
use App\Domain\User\User;
use App\Domain\User\Value\UserId;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ReviewUniqueVoter extends Voter
{
    public const NAME = 'APP_VOTER_REVIEW_UNIQUE';

    public function __construct(private ReviewRepository $reviewRepository)
    {
    }

    protected function supports($attribute, $subject): bool
    {
        return self::NAME === $attribute && $subject instanceof CreateReview;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof User) {
            return false;
        }

        if (!$subject instanceof CreateReview) {
            return false;
        }

        return !$this->reviewRepository->exists(Gtin::fromString($subject->gtin), UserId::fromInt($user->getId()));
    }
}
