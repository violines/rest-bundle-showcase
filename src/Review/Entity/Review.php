<?php

namespace App\Review\Entity;

use App\Review\Command\CreateReview;
use App\Review\Value\Rating;
use App\Review\Value\ReviewId;
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
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private string $comment;

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
        string $comment,
        int $productId,
        int $userId
    ) {
        $this->id = $reviewId->toInt();
        $this->taste = $taste;
        $this->ingredients = $ingredients;
        $this->healthiness = $healthiness;
        $this->packaging = $packaging;
        $this->availability = $availability;
        $this->comment = $comment;
        $this->productId = $productId;
        $this->userId = $userId;
    }

    public static function fromCreate(ReviewId $reviewId, CreateReview $review)
    {
        return new self(
            $reviewId,
            Rating::new($review->taste),
            Rating::new($review->ingredients),
            Rating::new($review->healthiness),
            Rating::new($review->packaging),
            Rating::new($review->availability),
            $review->comment,
            $review->productId,
            $review->userId
        );
    }
}
