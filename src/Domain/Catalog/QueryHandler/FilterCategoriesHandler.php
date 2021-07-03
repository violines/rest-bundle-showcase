<?php

declare(strict_types=1);

namespace App\Domain\Catalog\QueryHandler;

use App\Domain\Catalog\Query\FilterCategories;
use App\Domain\Catalog\Repository\CategoryListItemRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class FilterCategoriesHandler implements MessageHandlerInterface
{
    private CategoryListItemRepository $categoryListItemRepository;

    public function __construct(CategoryListItemRepository $categoryListItemRepository)
    {
        $this->categoryListItemRepository = $categoryListItemRepository;
    }

    public function __invoke(FilterCategories $filterCategories): array
    {
        return $this->categoryListItemRepository->match();
    }
}
