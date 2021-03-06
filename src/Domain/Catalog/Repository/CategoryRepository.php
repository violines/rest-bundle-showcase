<?php

namespace App\Domain\Catalog\Repository;

use App\Domain\Catalog\Category;
use App\Domain\Catalog\Value\CategoryId;

interface CategoryRepository
{
    /**
     * @return CategoryId
     */
    public function nextId(): CategoryId;

    /**
     * @param Category $category
     */
    public function save(Category $category): void;

    /**
     * @param string $title
     * @return bool
     */
    public function exists(string $title): bool;

    /**
     * @param CategoryId $categoryId
     * @return Category
     */
    public function find(CategoryId $categoryId): Category;
}
