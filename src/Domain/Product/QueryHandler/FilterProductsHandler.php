<?php

declare(strict_types=1);

namespace App\Domain\Product\QueryHandler;

use App\Domain\Product\Query\FilterProducts;
use App\Domain\Product\Repository\ProductListCriteria;
use App\Domain\Product\Repository\ProductListItemRepository;
use App\Domain\Product\Value\Language;
use App\Domain\Product\View\ProductListItem;
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
