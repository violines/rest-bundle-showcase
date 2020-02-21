<?php

declare(strict_types=1);

namespace App\Struct\Frontend;

use TerryApiBundle\Annotation\Struct;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Struct
 */
class Review
{
    /**
     * @Assert\Type("string")
     */
    public $gtin;

    /**
     * @Assert\Type("int")
     * @Assert\GreaterThanOrEqual(1)
     * @Assert\LessThanOrEqual(5)
     */
    public $taste;

    /**
     * @Assert\Type("int")
     * @Assert\GreaterThanOrEqual(1)
     * @Assert\LessThanOrEqual(5)
     */
    public $ingredients;

    /**
     * @Assert\Type("int")
     * @Assert\GreaterThanOrEqual(1)
     * @Assert\LessThanOrEqual(5)
     */
    public $healthiness;

    /**
     * @Assert\Type("int")
     * @Assert\GreaterThanOrEqual(1)
     * @Assert\LessThanOrEqual(5)
     */
    public $packaging;

    /**
     * @Assert\Type("int")
     * @Assert\GreaterThanOrEqual(1)
     * @Assert\LessThanOrEqual(5)
     */
    public $availability;

    /**
     * @Assert\Type("string")
     */
    public $comment;
}
