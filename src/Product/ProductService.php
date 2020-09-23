<?php

declare(strict_types=1);

namespace App\Product;

use App\Product\Exception\ProductNotExists;
use App\Product\Repository\CategoryRepository;
use App\Product\Value\Language;
use App\Product\View\CategoryView;
use App\Product\View\ProductView;
use App\Product\Repository\ProductRepository;
use App\Product\Repository\ProductViewRepository;

class ProductService
{
    private CategoryRepository $categoryRepository;

    private ProductRepository $productRepository;

    private ProductViewRepository $productViewRepository;

    public function __construct(CategoryRepository $categoryRepository, ProductRepository $productRepository, ProductViewRepository $productViewRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
        $this->productViewRepository = $productViewRepository;
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
        return $this->productViewRepository->findProductViews($language);
    }

    public function product(int $id, Language $language): ProductView
    {
        try {
            return $this->productViewRepository->findProductViewById($id, $language);
        } catch (\Throwable $e) {
            throw ProductNotExists::id($id);
        }
    }
}
