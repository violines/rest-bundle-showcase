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
    #[Assert\Valid]
    public $translations;
}
