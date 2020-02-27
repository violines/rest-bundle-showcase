<?php

declare(strict_types=1);

namespace App\Struct\Import;

use TerryApiBundle\Annotation\Struct;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Struct
 */
class Candy
{
    /**
     * @Assert\Type("string")
     * @var string
     */
    public $gtin;

    /**
     * @Assert\Type("int")
     * @var int
     */
    public $weight;

    /**
     * @Assert\Type("array")
     * @var CandyTranslation[]
     */
    public $translations;
}
