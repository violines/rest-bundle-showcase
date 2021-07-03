<?php

declare(strict_types=1);

namespace App\Domain\Catalog;

use App\Domain\Catalog\Exception\ProductNotExists;
use App\Domain\Catalog\Repository\ProductDetailCriteria;
use App\Domain\Catalog\Repository\ProductDetailRepository;
use App\Domain\Catalog\Value\Language;
use App\Domain\Catalog\Value\ProductId;
use App\Domain\Catalog\View\ProductDetail;

class ProductService
{
    public function __construct(private ProductDetailRepository $productDetailRepository)
    {
    }

    public function findProduct(ProductId $productId, Language $language): ProductDetail
    {
        $productDetailCriteria = ProductDetailCriteria::for($productId, $language);

        try {
            return $this->productDetailRepository->find($productDetailCriteria);
        } catch (\Throwable $e) {
            throw ProductNotExists::id($productId->toInt());
        }
    }
}
