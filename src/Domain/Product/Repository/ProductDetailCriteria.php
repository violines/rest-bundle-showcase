<?php

declare(strict_types=1);

namespace App\Domain\Product\Repository;

use App\Domain\Product\Value\Language;
use App\Domain\Product\Value\ProductId;

final class ProductDetailCriteria
{
    private ProductId $productId;

    private Language $language;

    private function __construct(ProductId $productId, Language $language)
    {
        $this->productId = $productId;
        $this->language = $language;
    }

    public static function for(ProductId $productId, Language $language): static
    {
        return new self($productId, $language);
    }

    public function getProductId(): ProductId
    {
        return $this->productId;
    }

    public function getLanguage(): Language
    {
        return $this->language;
    }
}
