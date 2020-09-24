<?php

namespace App\Product\Repository;

use App\Product\Entity\Product;
use App\Product\Value\ProductId;

interface ProductRepository
{
    /**
     * @return ProductId
     */
    public function nextId(): ProductId;

    /**
     * @param Product $product
     */
    public function saveProduct(Product $product): void;

    /**
     * @param string $gtin
     * @return bool
     */
    public function productExists(string $gtin): bool;

    /**
     * @param int $id
     * @return Product
     */
    public function findProduct(int $id): Product;

    /**
     * @return Product[]
     */
    public function findProducts(): array;
}
