<?php

namespace App\Domain\Category\Repository;

use App\Domain\Category\Category;
use App\Domain\Category\Value\CategoryId;

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
