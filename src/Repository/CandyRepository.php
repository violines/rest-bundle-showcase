<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Candy;
use App\Exception\PersistanceLayerException;
use App\Model\Candy as CandyModel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Candy|null find($id, $lockMode = null, $lockVersion = null)
 * @method Candy|null findOneBy(array $criteria, array $orderBy = null)
 * @method Candy[]    findAll()
 * @method Candy[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CandyRepository extends ServiceEntityRepository
{
    private const MAX_INSERT = 100;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Candy::class);
    }

    /**
     * @param CandyModel[]
     */
    public function insert(array $candies): void
    {
        $rowCount = count($candies);

        if (self::MAX_INSERT < $rowCount) {
            throw PersistanceLayerException::fromMaxInsert(self::MAX_INSERT, $rowCount);
        }

        $colCount = count((array) $candies[0]);

        $sql = '
            WITH 
                data (gtin, weight, language, title) AS (
                    VALUES
                        ' . $this->generateValueMatrix($rowCount, $colCount) . '
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

        $connection = $this->getEntityManager()->getConnection();

        $statement = $connection->prepare($sql);

        $count = 1;
        /** @var CandyModel $candy */
        foreach ($candies as $candy) {
            $statement->bindValue($count++, $candy->getGtin());
            $statement->bindValue($count++, $candy->getWeight());
            $statement->bindValue($count++, $candy->getLanguage());
            $statement->bindValue($count++, $candy->getTitle());
        }

        $statement->execute();
    }

    private function generateValueMatrix(int $rows, int $cols): string
    {
        $matrix = '';

        for ($i = 0; $i < $rows; $i++) {
            $matrix .= '(' . implode(',', array_fill(0, $cols, '?')) . ')';
            $matrix .= $i < $rows - 1 ? ',' : '';
        }

        return $matrix;
    }
}
