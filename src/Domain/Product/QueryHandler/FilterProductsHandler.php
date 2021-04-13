<?php

declare(strict_types=1);

namespace App\Domain\Product\QueryHandler;

use App\Domain\Product\Query\FilterProducts;
use App\Domain\Product\Repository\ProductViewCriteria;
use App\Domain\Product\Repository\ProductViewRepository;
use App\Domain\Product\View\ProductView;
use App\Domain\Review\Value\Rating;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class FilterProductsHandler implements MessageHandlerInterface
{
    private ProductViewRepository $productViewRepository;

    public function __construct(ProductViewRepository $productViewRepository)
    {
        $this->productViewRepository = $productViewRepository;
    }

    /**
     * @return ProductView[]
     */
    public function __invoke(FilterProducts $filterProducts): array
    {
        $criteria = ProductViewCriteria::withDefaults()
            ->andMinRating(Rating::fromInt($filterProducts->ratingFrom))
            ->andMaxRating(Rating::fromInt($filterProducts->ratingTo));

        return array_map(
            fn (ProductView $product) => $product->withLanguage($filterProducts->language),
            $this->productViewRepository->match($criteria)
        );
    }
}