<?php

namespace App\Domain\Product\Repository;

use App\Domain\Product\View\ProductDetail;

interface ProductDetailRepository
{
    public function find(ProductDetailCriteria $criteria): ProductDetail;
}
