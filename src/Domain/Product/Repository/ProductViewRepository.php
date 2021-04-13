<?php

namespace App\Domain\Product\Repository;

use App\Domain\Product\Value\ProductId;
use App\Domain\Product\View\ProductView as Product;
use App\Domain\Product\Repository\ProductViewCriteria as Criteria;

interface ProductViewRepository
{
    public function find(ProductId $id): Product;

    /**
     * @return Product[]
     */
    public function match(Criteria $criteria): array;
}
