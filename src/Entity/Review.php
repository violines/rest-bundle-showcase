<?php

namespace App\Entity;

use App\Struct\Frontend\Review as FrontendStruct;
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
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $taste;

    /**
     * @ORM\Column(type="integer")
     */
    private $ingredients;

    /**
     * @ORM\Column(type="integer")
     */
    private $healthiness;

    /**
     * @ORM\Column(type="integer")
     */
    private $packaging;

    /**
     * @ORM\Column(type="integer")
     */
    private $availability;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Candy", inversedBy="reviews")
     * @ORM\JoinColumn(nullable=false)
     */
    private $candy;

    public function __construct(
        int $taste,
        int $ingredients,
        int $healthiness,
        int $packaging,
        int $availability,
        string $comment,
        Candy $candyEntity
    ) {
        $this->taste = $taste;
        $this->ingredients = $ingredients;
        $this->healthiness = $healthiness;
        $this->packaging = $packaging;
        $this->availability = $availability;
        $this->comment = $comment;
        $this->candy = $candyEntity;
    }

    public static function fromStruct(FrontendStruct $struct, Candy $candyEntity)
    {
        return new self(
            $struct->taste,
            $struct->ingredients,
            $struct->healthiness,
            $struct->packaging,
            $struct->availability,
            $struct->comment,
            $candyEntity
        );
    }
}
