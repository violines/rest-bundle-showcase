<?php

namespace App\Review\Entity;

use App\Product\Value\ProductId;
use App\Review\Command\CreateReview;
use App\Review\Value\Comment;
use App\Review\Value\Rating;
use App\Review\Value\ReviewId;
use App\User\Value\UserId;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Infrastructure\Repository\ReviewDoctrineRepository")
 */
class Review
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer", options={"default"="nextval('review_id_seq'::regclass)"})
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\SequenceGenerator(sequenceName="review_id_seq", allocationSize=1, initialValue=0)
     */
    private int $id;

    /**
     * @ORM\Column(type="rating")
     */
    private Rating $taste;

    /**
     * @ORM\Column(type="rating")
     */
    private Rating $ingredients;

    /**
     * @ORM\Column(type="rating")
     */
    private Rating $healthiness;

    /**
     * @ORM\Column(type="rating")
     */
    private Rating $packaging;

    /**
     * @ORM\Column(type="rating")
     */
    private Rating $availability;

    /**
     * @ORM\Column(type="comment")
     */
    private Comment $comment;

    /**
     * @ORM\Column(type="integer")
     */
    private int $productId;

    /**
     * @ORM\Column(type="integer")
     */
    private int $userId;

    private function __construct(
        ReviewId $reviewId,
        Rating $taste,
        Rating $ingredients,
        Rating $healthiness,
        Rating $packaging,
        Rating $availability,
        Comment $comment,
        ProductId $productId,
        UserId $userId
    ) {
        $this->id = $reviewId->toInt();
        $this->taste = $taste;
        $this->ingredients = $ingredients;
        $this->healthiness = $healthiness;
        $this->packaging = $packaging;
        $this->availability = $availability;
        $this->comment = $comment;
        $this->productId = $productId->toInt();
        $this->userId = $userId->toInt();
    }

    public static function fromCreate(ReviewId $reviewId, CreateReview $review)
    {
        return new self(
            $reviewId,
            Rating::fromInt($review->taste),
            Rating::fromInt($review->ingredients),
            Rating::fromInt($review->healthiness),
            Rating::fromInt($review->packaging),
            Rating::fromInt($review->availability),
            Comment::fromString($review->comment),
            ProductId::fromInt($review->productId),
            UserId::fromInt($review->userId)
        );
    }
}
