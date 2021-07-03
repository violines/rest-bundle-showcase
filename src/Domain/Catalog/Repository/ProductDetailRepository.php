<?php

namespace App\Domain\Catalog\Repository;

use App\Domain\Catalog\Repository\ProductDetailCriteria;
use App\Domain\Catalog\View\ProductDetail;

interface ProductDetailRepository
{
    public function find(ProductDetailCriteria $criteria): ProductDetail;
}
