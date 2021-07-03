<?php

namespace App\Domain\Catalog\Repository;

use App\Domain\Catalog\View\CategoryListItem;

interface CategoryListItemRepository
{
    /**
     * @return CategoryListItem[]
     */
    public function match(): array;
}
