<?php

declare(strict_types=1);

namespace App\Domain\Category\QueryHandler;

use App\Domain\Category\Query\FilterCategories;
use App\Domain\Category\Repository\CategoryListItemRepository;
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
