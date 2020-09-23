<?php

namespace App\Product\Repository;

use App\Product\Entity\Product;

interface ProductRepository
{
    /**
     * @return int
     */
    public function nextId(): int;

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