<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Product\Repository\CategoryViewRepository;
use App\Domain\Product\Value\CategoryId;
use App\Domain\Product\Value\Language;
use App\Domain\Product\View\CategoryView;
use Doctrine\DBAL\Connection;

class CategoryViewSqlRepository implements CategoryViewRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function findCategoryView(CategoryId $categoryId, Language $language): CategoryView
    {
        $statement = $this->connection->createQueryBuilder()
            ->select(' category.key, category.sorting')
            ->from('category')
            ->andWhere('category.id = :categoryId')
            ->setParameter('categoryId', $categoryId->toInt())
            ->execute();

        $result = $statement->fetch();

        return new CategoryView($result['key'], $result['sorting']);
    }

    public function findCategoryViews(Language $language): array
    {
        $statement = $this->connection->createQueryBuilder()
            ->select(' category.key, category.sorting')
            ->from('category')
            ->execute();

        $rows = $statement->fetchAll();

        $categoryViews = [];

        foreach ($rows  as $row) {
            $categoryViews[] = new CategoryView($row['key'], $row['sorting']);
        }

        return $categoryViews;
    }
}
