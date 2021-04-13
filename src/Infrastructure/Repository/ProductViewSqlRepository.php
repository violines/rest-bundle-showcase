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
                MAX(product_translation.title) as title,
                product_translation.language,
                (AVG(review.taste) + AVG(review.ingredients) + AVG(review.healthiness) + AVG(review.packaging) + AVG(review.availability)) / 5 as average_rating
            ')
            ->from('product')
            ->leftJoin('product', 'review', 'review', 'review.product_id = product.id')
            ->leftJoin('product', 'product_translation', 'product_translation', 'product_translation.product_id = product.id')
            ->andWhere('product.id = :productId')
            ->setParameter('productId', $productId->toInt())
            ->groupBy('product.id, product_translation.language')
            ->execute();

        return $this->createView($statement->fetchAll());
    }

    public function match(ProductViewCriteria $citeria): array
    {
        $statement = $this->connection->createQueryBuilder()
            ->select('
                product.id,
                product.gtin,
                product.weight,
                MAX(product_translation.title) AS title,
                product_translation.language,
                (AVG(review.taste) + AVG(review.ingredients) + AVG(review.healthiness) + AVG(review.packaging) + AVG(review.availability)) / 5 as average_rating
            ')
            ->from('product')
            ->leftJoin('product', 'review', 'review', 'review.product_id = product.id')
            ->leftJoin('product', 'product_translation', 'product_translation', 'product_translation.product_id = product.id')
            ->groupBy('product.id, product_translation.language')
            ->andHaving('(AVG(review.taste) + AVG(review.ingredients) + AVG(review.healthiness) + AVG(review.packaging) + AVG(review.availability)) / 5 >= :ratingFrom')
            ->andHaving('(AVG(review.taste) + AVG(review.ingredients) + AVG(review.healthiness) + AVG(review.packaging) + AVG(review.availability)) / 5 <= :ratingTo')
            ->setParameter('ratingFrom', $citeria->minRatingAsInt())
            ->setParameter('ratingTo', $citeria->maxRatingAsInt())
            ->orderBy('product.id')
            ->execute();

        $rows = $statement->fetchAll();

        $map = [];
        foreach ($rows as $row) {
            $map[$row['id']][] = $row;
        }

        $productViews = [];
        foreach ($map as $viewRows) {
            $productViews[] = $this->createView($viewRows);
        }

        return $productViews;
    }

    private function createView($rows): ProductView
    {
        foreach ($rows as $row) {
            $gtin = $row['gtin'];
            $weight = $row['weight'];
            $names[$row['language']] = $row['title'];
            $averageRating = $row['average_rating'];
        }

        return new ProductView($gtin, $weight, $names, (int)$averageRating);
    }
}
