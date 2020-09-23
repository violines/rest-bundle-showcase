<?php

namespace App\Product\Repository;

use App\Product\Entity\Category;

interface CategoryRepository
{
    /**
     * @return int
     */
    public function nextId(): int;

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
     * @param int $id
     * @return Category
     */
    public function findCategory(int $id): Category;

    /**
     * @return Category[]
     */
    public function findCategories(): array;
}
