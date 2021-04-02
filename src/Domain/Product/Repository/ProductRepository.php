<?php

namespace App\Domain\Product\Repository;

use App\Domain\Product\Entity\Product;
use App\Domain\Product\Value\Gtin;
use App\Domain\Product\Value\ProductId;

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
     * @param Gtin $gtin
     * @return bool
     */
    public function productExists(Gtin $gtin): bool;

    /**
     * @param ProductId $productId
     * @return Product
     */
    public function findProduct(ProductId $productId): Product;
}
