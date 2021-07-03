<?php

namespace App\Domain\Catalog\Repository;

use App\Domain\Catalog\Repository\ProductListCriteria;
use App\Domain\Catalog\View\ProductListItem;

interface ProductListItemRepository
{
    /**
     * @return ProductListItem[]
     */
    public function match(ProductListCriteria $criteria): array;
}
