<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Product\Query\FilterProducts;
use App\Domain\Product\Repository\ProductViewRepository;
use App\Domain\Product\Value\Language;
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

    public function findProductView(ProductId $productId, Language $language): ProductView
    {
        $statement = $this->connection->createQueryBuilder()
            ->select('
                product.id,
                product.gtin,
                product.weight,
                max(product_translation.title) as title,
                AVG(review.taste) as taste,
                AVG(review.ingredients) as ingredients,
                AVG(review.healthiness) as healthiness,
                AVG(review.packaging) as packaging,
                AVG(review.availability) as availability
            ')
            ->from('product')
            ->leftJoin('product', 'review', 'review', 'review.product_id = product.id')
            ->leftJoin('product', 'product_translation', 'product_translation', 'product_translation.product_id = product.id')
            ->andWhere('product.id = :productId AND product_translation.language = :language')
            ->setParameter('productId', $productId->toInt())
            ->setParameter('language', $language->toString())
            ->groupBy('product.id')
            ->execute();

        $result = $statement->fetch();

        $averageRating = ($result['taste'] + $result['ingredients'] + $result['healthiness'] + $result['packaging'] + $result['availability']) / 5;

        return new ProductView($result['gtin'], $result['weight'], $result['title'], (int)$averageRating);
    }

    public function findProductViews(FilterProducts $filterProducts): array
    {
        $statement = $this->connection->createQueryBuilder()
            ->select('
                product.id,
                product.gtin,
                product.weight,
                max(product_translation.title) as title,
                AVG(review.taste) as taste,
                AVG(review.ingredients) as ingredients,
                AVG(review.healthiness) as healthiness,
                AVG(review.packaging) as packaging,
                AVG(review.availability) as availability
            ')
            ->from('product')
            ->leftJoin('product', 'review', 'review', 'review.product_id = product.id')
            ->leftJoin('product', 'product_translation', 'product_translation', 'product_translation.product_id = product.id')
            ->andWhere('product_translation.language = :language')
            ->setParameter('language', $filterProducts->language->toString())
            ->groupBy('product.id')
            ->execute();

        $rows = $statement->fetchAll();

        $productViews = [];

        foreach ($rows  as $row) {
            $averageRating = (int)(($row['taste'] + $row['ingredients'] + $row['healthiness'] + $row['packaging'] + $row['availability']) / 5);

            if ($averageRating >= $filterProducts->ratingFrom && $averageRating <= $filterProducts->ratingTo) {
                $productViews[] = new ProductView($row['gtin'], $row['weight'], $row['title'], (int)$averageRating);
            }
        }

        return $productViews;
    }
}
