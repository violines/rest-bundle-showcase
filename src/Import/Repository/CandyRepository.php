<?php

declare(strict_types=1);

namespace App\Import\Repository;

use App\Exception\PersistenceLayerException;
use App\Import\Model\Candy;
use Doctrine\ORM\EntityManagerInterface;

class CandyRepository implements CandyInterface
{
    private const MAX_INSERT = 1000;

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Candy[]
     */
    public function saveMany(array $candies): void
    {
        $rowCount = count($candies);

        if (self::MAX_INSERT < $rowCount) {
            throw PersistenceLayerException::fromMaxInsert(self::MAX_INSERT, $rowCount);
        }

        $sql = '
            WITH 
                data (gtin, weight, language, title) AS (
                    VALUES
                        ' . $this->generateValueMatrix($rowCount) . '
                ), 
                candy AS (
                    INSERT INTO candy (gtin, weight)
                        SELECT DISTINCT gtin, CAST(weight AS int4) FROM data
                        ON CONFLICT (gtin) DO UPDATE SET weight = EXCLUDED.weight
                    RETURNING gtin, id AS candy_id
                )
            INSERT INTO candy_translation (candy_id, language, title)
                SELECT candy_id, language, title
                FROM data
                JOIN candy USING (gtin)
                ON CONFLICT (candy_id, language) DO UPDATE SET title = EXCLUDED.title;
        ';

        $connection = $this->entityManager->getConnection();

        $statement = $connection->prepare($sql);

        /** @var Candy $candy */
        foreach ($candies as $i => $candy) {
            foreach ($candy->toArray() as $name => $value) {
                $statement->bindValue($name . $i, $value);
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
