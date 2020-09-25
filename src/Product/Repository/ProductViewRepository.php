<?php

namespace App\Product\Repository;

use App\Product\Value\Language;
use App\Product\Value\ProductId;
use App\Product\View\ProductView;

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
    public function findProductViews(Language $language): array;
}
