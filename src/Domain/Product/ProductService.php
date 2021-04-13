<?php

declare(strict_types=1);

namespace App\Domain\Product;

use App\Domain\Product\Exception\ProductNotExists;
use App\Domain\Product\Value\Language;
use App\Domain\Product\View\ProductView;
use App\Domain\Product\Repository\ProductViewRepository;
use App\Domain\Product\Value\ProductId;

class ProductService
{
    private ProductViewRepository $productViewRepository;

    public function __construct(ProductViewRepository $productViewRepository)
    {
        $this->productViewRepository = $productViewRepository;
    }

    public function findProduct(ProductId $productId, Language $language): ProductView
    {
        try {
            return $this->productViewRepository->find($productId)->withLanguage($language);
        } catch (\Throwable $e) {
            throw ProductNotExists::id($productId->toInt());
        }
    }
}
