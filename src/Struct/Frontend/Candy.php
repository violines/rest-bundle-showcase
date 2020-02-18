<?php

declare(strict_types=1);

namespace App\Struct\Frontend;

use TerryApiBundle\Annotation\Struct;
use Symfony\Component\Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Struct
 */
class Candy
{
    /**
     * @Assert\Type("string")
     */
    public $gtin;

    /**
     * @Assert\Type("int")
     */
    public $weight;

    /**
     * @Assert\Type("string")
     */
    public $name;

    /**
     * @Serializer\Annotation\SerializedName("average_rating")
     */
    public $averageRating;

    public function __construct(string $gtin, int $weight, string $name, ?array $averageRating)
    {
        $this->gtin = $gtin;
        $this->weight = $weight;
        $this->name = $name;
        $this->averageRating = $averageRating;
    }
}
