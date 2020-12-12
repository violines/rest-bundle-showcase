<?php

declare(strict_types=1);

namespace App\Import\Model;

use Violines\RestBundle\HttpApi\HttpApi;
use Symfony\Component\Validator\Constraints as Assert;

#[HttpApi]
class Product
{
    /**
     * @var string
     */
    #[Assert\Type("string")]
    public $gtin;

    /**
     * @var int
     */
    #[Assert\Type("int")]
    public $weight;

    /**
     * @var ProductTranslation[]
     */
    #[Assert\Type("array")]
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
