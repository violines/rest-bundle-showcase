<?php

namespace App\Domain\Product\Repository;

use App\Domain\Product\Entity\Category;
use App\Domain\Product\Value\CategoryId;

interface CategoryRepository
{
    /**
     * @return CategoryId
     */
    public function nextId(): CategoryId;

    /**
     * @param Category $category
     */
    public function saveCategory(Category $category): void;

    /**
     * @param string $title
     * @return bool
     */
    public function categoryExists(string $title): bool;

    /**
     * @param CategoryId $categoryId
     * @return Category
     */
    public function findCategory(CategoryId $categoryId): Category;
}
