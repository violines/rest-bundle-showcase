<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Product\Repository\ProductDetailRepository;
use App\Domain\Product\Repository\ProductListCriteria;
use App\Domain\Product\Repository\ProductListItemRepository;
use App\Domain\Product\Value\ProductId;
use App\Domain\Product\View\ProductDetail;
use App\Domain\Product\View\ProductListItem;
use Doctrine\DBAL\Connection;

class ProductViewSqlRepository implements ProductListItemRepository, ProductDetailRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function find(ProductId $productId): ProductDetail
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

        $row = $statement->fetch();

        return new ProductDetail($row['gtin'], $weight = $row['weight'], \json_decode($row['titles'], true), (int)$row['average_rating']);
    }

    public function match(ProductListCriteria $citeria): array
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
            ->setParameter('ratingFrom', $citeria->getMinRating()->toInt())
            ->setParameter('ratingTo', $citeria->getMaxRating()->toInt())
            ->setFirstResult($citeria->offset())
            ->setMaxResults($citeria->limit())
            ->orderBy('product.id')
            ->execute();

        $rows = $statement->fetchAll();

        $productViews = [];
        foreach ($rows as $row) {
            $productViews[] = new ProductListItem($row['gtin'], $weight = $row['weight'], \json_decode($row['titles'], true), (int)$row['average_rating']);
        }

        return $productViews;
    }
}
