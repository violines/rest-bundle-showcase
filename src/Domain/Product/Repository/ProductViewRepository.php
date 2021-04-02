<?php

namespace App\Domain\Product\Repository;

use App\Domain\Product\Command\Filter;
use App\Domain\Product\Value\Language;
use App\Domain\Product\Value\ProductId;
use App\Domain\Product\View\ProductView;

interface ProductViewRepository
{
    /**
     * @param ProductId $productId
     * @return ProductView
     */
    public function findProductView(ProductId $productId, Language $language): ProductView;

    /**
     * @return ProductView[]
     */
    public function findProductViews(Language $language, Filter $filter): array;
}
