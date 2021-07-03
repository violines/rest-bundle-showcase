<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Catalog\View\CategoryListItem;
use App\Domain\Catalog\Repository\CategoryListItemRepository;
use Doctrine\DBAL\Connection;

class CategoryViewSqlRepository implements CategoryListItemRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function match(): array
    {
        $statement = $this->connection->createQueryBuilder()
            ->select(' category.key, category.sorting')
            ->from('category')
            ->execute();

        $rows = $statement->fetchAll();

        $categoryViews = [];

        foreach ($rows  as $row) {
            $categoryViews[] = new CategoryListItem($row['key'], $row['sorting']);
        }

        return $categoryViews;
    }
}
