<?php

declare(strict_types=1);

namespace App\Import\Model;

use TerryApiBundle\HttpApi\HttpApi;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @HttpApi
 */
class Product
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
     * @var ProductTranslation[]
     */
    public $translations;

    public function toArray()
    {
        return [
            'gtin' => $this->gtin,
            'weight' => $this->weight,
            'translations' => array_map(fn (ProductTranslation $t) => $t->toArray(), $this->translations)
        ];
    }
}
