<?php

declare(strict_types=1);

namespace App\Product;

use App\Product\Exception\ProductNotExists;
use App\Product\Repository\CategoryRepository;
use App\Product\Value\Language;
use App\Product\View\CategoryView;
use App\Product\View\ProductView;
use App\Product\Repository\ProductRepository;

class ProductService
{
    private CategoryRepository $categoryRepository;

    private ProductRepository $productRepository;

    public function __construct(CategoryRepository $categoryRepository, ProductRepository $productRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * @return CategoryView[]
     */
    public function categories()
    {
        $categories = [];

        foreach ($this->categoryRepository->findCategories() as $category) {
            $categories[] = CategoryView::fromEntity($category);
        }

        return $categories;
    }

    /**
     * @return ProductView[]
     */
    public function products(Language $language): array
    {
        $products = [];

        foreach ($this->productRepository->findProducts() as $product) {
            $products[] = ProductView::fromEntity($product, $language->toString());
        }

        return $products;
    }

    public function product(int $id, Language $language): ProductView
    {
        try {
            $product = $this->productRepository->findProduct($id);
        } catch (\Throwable $e) {
            throw ProductNotExists::id($id);
        }

        return ProductView::fromEntity($product, $language->toString());
    }
}
