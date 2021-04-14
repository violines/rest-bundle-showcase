<?php

declare(strict_types=1);

namespace App\Domain\Product\QueryHandler;

use App\Domain\Product\Query\FilterCategories;
use App\Domain\Product\Repository\CategoryViewRepository;
use App\Domain\Product\Value\Language;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class FilterCategoriesHandler implements MessageHandlerInterface
{
    private CategoryViewRepository $categoryViewRepository;

    public function __construct(CategoryViewRepository $categoryViewRepository)
    {
        $this->categoryViewRepository = $categoryViewRepository;
    }

    public function __invoke(FilterCategories $findCategories): array
    {
        return $this->categoryViewRepository->match();
    }
}
