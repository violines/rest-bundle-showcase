<?php

declare(strict_types=1);

namespace App\Product\Command;

use TerryApiBundle\HttpApi\HttpApi;
use Symfony\Component\Serializer;

/**
 * @HttpApi(requestInfoSource=HttpApi::QUERY_STRING)
 */
class FilterProduct
{
    public $id;
    
    /**
     * @Serializer\Annotation\SerializedName("filter_price_from")
     */
    public $priceFrom;

    /**
     * @Serializer\Annotation\SerializedName("filter_price_to")
     */
    public $priceTo;
}
