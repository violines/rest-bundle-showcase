<?php

declare(strict_types=1);

namespace App\Domain\Catalog\QueryHandler;

use App\Domain\Catalog\Query\FilterProducts;
use App\Domain\Catalog\Repository\ProductListCriteria;
use App\Domain\Catalog\Repository\ProductListItemRepository;
use App\Domain\Catalog\Value\Language;
use App\Domain\Catalog\View\ProductListItem;
use App\Domain\Review\Value\Rating;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class FilterProductsHandler implements MessageHandlerInterface
{
    public function __construct(private ProductListItemRepository $productListItemRepository)
    {
    }

    /**
     * @return ProductListItem[]
     */
    public function __invoke(FilterProducts $filterProducts): array
    {
        $criteria = ProductListCriteria::withDefaults(Language::fromString($filterProducts->language))
            ->andMinRating(Rating::fromInt($filterProducts->ratingFrom))
            ->andMaxRating(Rating::fromInt($filterProducts->ratingTo))
            ->forPage($filterProducts->page);

        return $this->productListItemRepository->match($criteria);
    }
}
