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
    public function saveMany(array $candies): void
    {
        $rowCount = 0;
        foreach ($candies as $product) {
            $rowCount += count($product->translations);
        }

        if (self::MAX_INSERT < $rowCount) {
            throw PersistenceLayerException::fromMaxInsert(self::MAX_INSERT, $rowCount);
        }

        $sql = '
            WITH 
                data (gtin, weight, language, title) AS (
                    VALUES
                        ' . $this->generateValueMatrix($rowCount) . '
                ), 
                product AS (
                    INSERT INTO product (gtin, weight)
                        SELECT DISTINCT gtin, CAST(weight AS int4) FROM data
                        ON CONFLICT (gtin) DO UPDATE SET weight = EXCLUDED.weight
                    RETURNING gtin, id AS product_id
                )
            INSERT INTO product_translation (product_id, language, title)
                SELECT product_id, language, title
                FROM data
                JOIN product USING (gtin)
                ON CONFLICT (product_id, language) DO UPDATE SET title = EXCLUDED.title;
        ';

        $connection = $this->entityManager->getConnection();

        $statement = $connection->prepare($sql);

        /** @var Product $product */
        $i = 0;
        foreach ($candies as $product) {
            $mappedProduct = $product->toArray();
            foreach ($mappedProduct['translations'] as $mappedTranslation) {
                $statement->bindValue('gtin' . $i, $mappedProduct['gtin']);
                $statement->bindValue('weight' . $i, $mappedProduct['weight']);
                $statement->bindValue('language' . $i, $mappedTranslation['language']);
                $statement->bindValue('title' . $i, $mappedTranslation['title']);
                $i++;
            }
        }

        $statement->execute();
    }

    private function generateValueMatrix(int $rows): string
    {
        $matrix = '';

        for ($i = 0; $i < $rows; $i++) {
            $matrix .= "(:gtin$i, :weight$i, :language$i, :title$i)";
            $matrix .= $i < $rows - 1 ? ',' : '';
        }

        return $matrix;
    }
}
