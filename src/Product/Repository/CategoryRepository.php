<?php

namespace App\Product\Repository;

use App\Product\Entity\Category;
use App\Product\Value\CategoryId;

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
