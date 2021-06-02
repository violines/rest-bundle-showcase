<?php

namespace App\Domain\Category\Repository;

use App\Domain\Category\View\CategoryListItem;

interface CategoryListItemRepository
{
    /**
     * @return CategoryListItem[]
     */
    public function match(): array;
}
