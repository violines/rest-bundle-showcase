<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Product\Repository\ProductViewRepository;
use App\Product\Value\Language;
use App\Product\View\ProductView;
use Doctrine\DBAL\Connection;

class ProductViewSqlRepository implements ProductViewRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function findProductViewById(int $id, Language $language): ProductView
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
            ->setParameter('productId', $id)
            ->setParameter('language', $language->toString())
            ->groupBy('product.id')
            ->execute();

        $result = $statement->fetch();

        $averageRating = ($result['taste'] + $result['ingredients'] + $result['healthiness'] + $result['packaging'] + $result['availability']) / 5;

        return new ProductView($result['gtin'], $result['weight'], $result['title'], (int)$averageRating);
    }

    public function findProductViews(Language $language): array
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
            ->setParameter('language', $language->toString())
            ->groupBy('product.id')
            ->execute();

        $rows = $statement->fetchAll();

        $productViews = [];

        foreach ($rows  as $row) {
            $averageRating = ($row['taste'] + $row['ingredients'] + $row['healthiness'] + $row['packaging'] + $row['availability']) / 5;
            $productViews[] = new ProductView($row['gtin'], $row['weight'], $row['title'], (int)$averageRating);
        }

        return $productViews;
    }
}
