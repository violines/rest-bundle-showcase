<?php

namespace App\Product\Repository;

use App\Product\Value\CategoryId;
use App\Product\Value\Language;
use App\Product\View\CategoryView;

interface CategoryViewRepository
{
    /**
     * @param CategoryId $categoryId
     * @return CategoryView
     */
    public function findCategoryView(CategoryId $categoryId, Language $language): CategoryView;

    /**
     * @return CategoryView[]
     */
    public function findCategoryViews(Language $language): array;
}
