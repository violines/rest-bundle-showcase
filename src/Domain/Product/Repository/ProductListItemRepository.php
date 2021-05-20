<?php

namespace App\Domain\Product\Repository;

use App\Domain\Product\View\ProductListItem;

interface ProductListItemRepository
{
    /**
     * @return ProductListItem[]
     */
    public function match(ProductListCriteria $criteria): array;
}
