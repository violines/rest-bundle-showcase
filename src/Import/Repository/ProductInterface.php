<?php

namespace App\Import\Repository;

use App\Import\Model\Product;

interface ProductInterface
{
    /**
     * @param Product[]
     */
    public function saveMany(array $candies): void;
}
