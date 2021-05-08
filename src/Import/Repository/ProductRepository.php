<?php

declare(strict_types=1);

namespace App\Import\Repository;

use App\Import\Model\Product;
use App\Infrastructure\Exception\PersistenceLayerException;
use Doctrine\ORM\EntityManagerInterface;

class ProductRepository implements ProductInterface
{
    private const MAX_INSERT = 1000;

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Product[]
     */
    public function saveMany(array $products): void
    {
        $rowCount = count($products);

        if (self::MAX_INSERT < $rowCount) {
            throw PersistenceLayerException::fromMaxInsert(self::MAX_INSERT, $rowCount);
        }

        $sql = '
            INSERT INTO product (gtin, weight, titles)
                VALUES
                    ' . $this->generateValueMatrix($rowCount) . '
                ON CONFLICT (gtin) DO UPDATE SET weight = EXCLUDED.weight, titles = EXCLUDED.titles
        ';

        $connection = $this->entityManager->getConnection();

        $statement = $connection->prepare($sql);

        /** @var Product $product */
        $i = 0;
        foreach ($products as $product) {
            $titles = [];
            foreach ($product->translations as $translation) {
                $titles += $translation->titleMap();
            }

            $statement->bindValue('gtin' . $i, $product->gtin);
            $statement->bindValue('weight' . $i, $product->weight);
            $statement->bindValue('titles' . $i, json_encode($titles));
            $i++;
        }

        $statement->execute();
    }

    private function generateValueMatrix(int $rows): string
    {
        $matrix = '';

        for ($i = 0; $i < $rows; $i++) {
            $matrix .= "(:gtin$i, :weight$i, :titles$i)";
            $matrix .= $i < $rows - 1 ? ',' : '';
        }

        return $matrix;
    }
}
