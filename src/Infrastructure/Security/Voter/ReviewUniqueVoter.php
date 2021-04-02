<?php

namespace App\Infrastructure\Security\Voter;

use App\Infrastructure\Repository\ReviewDoctrineRepository;
use App\Domain\Review\Command\CreateReview;
use App\Domain\User\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ReviewUniqueVoter extends Voter
{
    public const NAME = 'APP_VOTER_REVIEW_UNIQUE';

    private ReviewDoctrineRepository $reviewRepository;

    public function __construct(ReviewDoctrineRepository $reviewRepository)
    {
        $this->reviewRepository = $reviewRepository;
    }

    protected function supports($attribute, $subject)
    {
        return self::NAME === $attribute && $subject instanceof CreateReview;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof User) {
            return false;
        }

        if (!$subject instanceof CreateReview) {
            return false;
        }

        return $this->reviewRepository->findOneByGtinAndUser($subject->gtin, $user) === null;
    }
}
