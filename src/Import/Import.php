<?php

declare(strict_types=1);

namespace App\Import;

use App\Import\Model\Product;
use App\Import\Model\Review;
use App\Import\Repository\ProductInterface;
use App\Import\Repository\ReviewInterface;

class Import
{
    private ProductInterface $productRepository;

    private ReviewInterface $reviewRepository;

    public function __construct(
        ProductInterface $productRepository,
        ReviewInterface $reviewRepository
    ) {
        $this->productRepository = $productRepository;
        $this->reviewRepository = $reviewRepository;
    }

    /**
     * @param Product[] $products
     */
    public function products(array $products): void
    {
        $this->productRepository->saveMany($products);
    }

    /**
     * @param Review[] $reviews
     */
    public function reviews(array $reviews): void
    {
        $this->reviewRepository->saveMany($reviews);
    }
}
