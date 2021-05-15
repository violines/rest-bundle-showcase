<?php

namespace App\Domain\Product\Repository;

use App\Domain\Product\View\ProductListItem;
use App\Domain\Product\Repository\ProductListCriteria;

interface ProductListItemRepository
{
    /**
     * @return ProductListItem[]
     */
    public function match(ProductListCriteria $criteria): array;
}
