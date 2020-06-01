<?php

declare(strict_types=1);

namespace App\DTO\Import;

use TerryApiBundle\Annotation\HTTPApi;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @HTTPApi
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
