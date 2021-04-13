<?php

namespace App\Domain\Product\Repository;

use App\Domain\Product\Value\CategoryId;
use App\Domain\Product\Value\Language;
use App\Domain\Product\View\CategoryView;

interface CategoryViewRepository
{
    /**
     * @param CategoryId $categoryId
     * @return CategoryView
     */
    public function find(CategoryId $categoryId): CategoryView;

    /**
     * @return CategoryView[]
     */
    public function match(): array;
}
