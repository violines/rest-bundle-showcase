<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use App\Entity\User;
use App\Repository\ReviewRepository;
use App\Struct\Frontend\Review;

class ReviewUniqueVoter extends Voter
{
    public const NAME = 'APP_VOTER_REVIEW_UNIQUE';

    private ReviewRepository $reviewRepository;

    public function __construct(ReviewRepository $reviewRepository)
    {
        $this->reviewRepository = $reviewRepository;
    }

    protected function supports($attribute, $subject)
    {
        return self::NAME === $attribute && $subject instanceof Review;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof User) {
            return false;
        }

        if (!$subject instanceof Review) {
            return false;
        }

        return $this->reviewRepository->findOneByGtinAndUser($subject->gtin, $user) === null;
    }
}
