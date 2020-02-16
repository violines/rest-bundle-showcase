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
     * @Serializer\Annotation\SerializedName("weight")
     * @Assert\Type("int")
     * @Assert\Positive
     */
    public $weight;

    /**
     * @Serializer\Annotation\SerializedName("name")
     * @Assert\Type("string")
     */
    public $name;

    /**
     * @var Rating[]
     * @Serializer\Annotation\SerializedName("ratings")
     * @Assert\Valid
     */
    public $ratings;
}
