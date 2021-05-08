<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Product\Repository\ProductViewCriteria;
use App\Domain\Product\Repository\ProductViewRepository;
use App\Domain\Product\Value\ProductId;
use App\Domain\Product\View\ProductView;
use Doctrine\DBAL\Connection;

class ProductViewSqlRepository implements ProductViewRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function find(ProductId $productId): ProductView
    {
        $statement = $this->connection->createQueryBuilder()
            ->select('
                product.id,
                product.gtin,
                product.weight,
                product.titles,
                (AVG(review.taste) + AVG(review.ingredients) + AVG(review.healthiness) + AVG(review.packaging) + AVG(review.availability)) / 5 as average_rating
            ')
            ->from('product')
            ->leftJoin('product', 'review', 'review', 'review.product_id = product.id')
            ->andWhere('product.id = :productId')
            ->setParameter('productId', $productId->toInt())
            ->groupBy('product.id')
            ->execute();

        return $this->createView($statement->fetch());
    }

    public function match(ProductViewCriteria $citeria): array
    {
        $statement = $this->connection->createQueryBuilder()
            ->select('
                product.id,
                product.gtin,
                product.weight,
                product.titles,
                (AVG(review.taste) + AVG(review.ingredients) + AVG(review.healthiness) + AVG(review.packaging) + AVG(review.availability)) / 5 as average_rating
            ')
            ->from('product')
            ->leftJoin('product', 'review', 'review', 'review.product_id = product.id')
            ->groupBy('product.id')
            ->andHaving('(AVG(review.taste) + AVG(review.ingredients) + AVG(review.healthiness) + AVG(review.packaging) + AVG(review.availability)) / 5 >= :ratingFrom')
            ->andHaving('(AVG(review.taste) + AVG(review.ingredients) + AVG(review.healthiness) + AVG(review.packaging) + AVG(review.availability)) / 5 <= :ratingTo')
            ->setParameter('ratingFrom', $citeria->minRatingAsInt())
            ->setParameter('ratingTo', $citeria->maxRatingAsInt())
            ->orderBy('product.id')
            ->execute();

        $rows = $statement->fetchAll();

        $productViews = [];
        foreach ($rows as $row) {
            $productViews[] = $this->createView($row);
        }

        return $productViews;
    }

    private function createView($row): ProductView
    {
        return new ProductView($row['gtin'], $weight = $row['weight'], \json_decode($row['titles'], true), (int)$row['average_rating']);
    }
}
