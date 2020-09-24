<?php

namespace App\Product\Repository;

use App\Product\Value\Language;
use App\Product\View\ProductView;

interface ProductViewRepository
{
    /**
     * @param int $id
     * @return ProductView
     */
    public function findProductView(int $id, Language $language): ProductView;

    /**
     * @return ProductView[]
     */
    public function findProductViews(Language $language): array;
}
