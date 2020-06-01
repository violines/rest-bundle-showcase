<?php

namespace App\Entity;

use App\DTO\Frontend\Review as FrontendReview;
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
     * @ORM\Column(type="integer")
     */
    private int $taste;

    /**
     * @ORM\Column(type="integer")
     */
    private int $ingredients;

    /**
     * @ORM\Column(type="integer")
     */
    private int $healthiness;

    /**
     * @ORM\Column(type="integer")
     */
    private $packaging;

    /**
     * @ORM\Column(type="integer")
     */
    private int $availability;

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

    public function __construct(
        int $taste,
        int $ingredients,
        int $healthiness,
        int $packaging,
        int $availability,
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

    public static function fromDTO(FrontendReview $frontenReview, Candy $candyEntity, User $userEntity)
    {
        return new self(
            $frontenReview->taste,
            $frontenReview->ingredients,
            $frontenReview->healthiness,
            $frontenReview->packaging,
            $frontenReview->availability,
            $frontenReview->comment,
            $candyEntity,
            $userEntity
        );
    }
}
