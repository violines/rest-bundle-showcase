<?php

declare(strict_types=1);

namespace App\Domain\Product\QueryHandler;

use App\Domain\Product\Query\FilterProducts;
use App\Domain\Product\Repository\ProductViewRepository;
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
        return $this->productViewRepository->findProductViews($filterProducts);
    }
}
