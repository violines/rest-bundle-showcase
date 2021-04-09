<?php

declare(strict_types=1);

namespace App\Domain\Product\QueryHandler;

use App\Domain\Product\Query\FindCategories;
use App\Domain\Product\Repository\CategoryViewRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class FindCategoriesHandler implements MessageHandlerInterface
{
    private CategoryViewRepository $categoryViewRepository;

    public function __construct(CategoryViewRepository $categoryViewRepository)
    {
        $this->categoryViewRepository = $categoryViewRepository;
    }

    public function __invoke(FindCategories $findCategories): array
    {
        return $this->categoryViewRepository->findCategoryViews($findCategories->language);
    }
}
