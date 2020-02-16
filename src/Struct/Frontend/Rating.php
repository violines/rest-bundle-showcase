<?php

declare(strict_types=1);

namespace App\Struct\Frontend;

use TerryApiBundle\Annotation\Struct;
use Symfony\Component\Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Struct
 */
class Rating
{
    /**
     * @Serializer\Annotation\SerializedName("amount_of_stars")
     * @Assert\Positive
     */
    public $stars;

    /**
     * @Serializer\Annotation\SerializedName("comment")
     * @Assert\Type("string")
     */
    public $comment;
}
