<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Infrastructure\Exception\NotFoundException;
use App\Product\Exception\ProductNotExists;
use App\Product\ProductService;
use App\Product\Value\Language;
use App\Product\Value\ProductId;
use App\Product\View\ProductView;
use Symfony\Component\Routing\Annotation\Route;

class ProductController
{
    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    #[Route('/{_locale}/categories', methods: ['GET'], name:'frontend_categories', requirements:['_locale' => 'en|de'])]
    public function findCategories(string $_locale): array
    {
        return $this->productService->findCategories(Language::fromString($_locale));
    }

    #[Route('/{_locale}/products', methods: ['GET'], name:'frontend_products', requirements:['_locale' => 'en|de'])]
    public function findProducts(string $_locale): array
    {
        return $this->productService->findProducts(Language::fromString($_locale));
    }

    #[Route('/{_locale}/product/{id}', methods: ['GET'], name:'frontend_product', requirements:['_locale' => 'en|de'])]
    public function findProduct(int $id, string $_locale): ProductView
    {
        try {
            $product = $this->productService->findProduct(ProductId::fromInt($id), Language::fromString($_locale));
        } catch (ProductNotExists $e) {
            throw NotFoundException::resource();
        }

        return $product;
    }
}
