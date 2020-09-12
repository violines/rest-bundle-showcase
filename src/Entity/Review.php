<?php

namespace App\Entity;

use App\CommandObject\Review as ReviewCommandObject;
use App\ValueObject\Rating;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReviewRepository")
 */
class Review
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer", options={"default"="nextval('review_id_seq'::regclass)"})
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\SequenceGenerator(sequenceName="review_id_seq", allocationSize=1, initialValue=1)
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Candy", inversedBy="reviews")
     * @ORM\JoinColumn(nullable=false)
     */
    private Candy $candy;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="reviews")
     * @ORM\JoinColumn(nullable=false)
     */
    private User $user;

    private function __construct(
        Rating $taste,
        Rating $ingredients,
        Rating $healthiness,
        Rating $packaging,
        Rating $availability,
        string $comment,
        Candy $candyEntity,
        User $userEntity
    ) {
        $this->taste = $taste;
        $this->ingredients = $ingredients;
        $this->healthiness = $healthiness;
        $this->packaging = $packaging;
        $this->availability = $availability;
        $this->comment = $comment;
        $this->candy = $candyEntity;
        $this->user = $userEntity;
    }

    public static function fromCommandObject(ReviewCommandObject $review, Candy $candyEntity, User $userEntity)
    {
        return new self(
            Rating::new($review->taste),
            Rating::new($review->ingredients),
            Rating::new($review->healthiness),
            Rating::new($review->packaging),
            Rating::new($review->availability),
            $review->comment,
            $candyEntity,
            $userEntity
        );
    }
}
