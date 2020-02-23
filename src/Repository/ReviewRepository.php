<?php

namespace App\Repository;

use App\Entity\Candy;
use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Rating|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rating|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rating[]    findAll()
 * @method Rating[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewRepository extends ServiceEntityRepository
{
    private string $insertSql = '';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    public function averageByCandy(Candy $candy): array
    {
        $result = $this->createQueryBuilder('rating')
            ->select('
                AVG(rating.taste) as taste,
                AVG(rating.ingredients) as ingredients,
                AVG(rating.healthiness) as healthiness,
                AVG(rating.packaging) as packaging,
                AVG(rating.availability) as availability
            ')
            ->andWhere('rating.candy = :candy')
            ->setParameter('candy', $candy)
            ->getQuery()
            ->getResult();

        return array_map(fn ($p) => (int) $p, current($result));
    }

    public function insert(array $row, bool $flush = false): void
    {
        $connection = $this->getEntityManager()->getConnection();

        // this uses the DBAL SQL QueryBuilder not the ORM's DQL builder 
        $this->insertSql .= $connection->createQueryBuilder()
            ->insert('review')
            ->values($row)
            ->getSQL() . ';';

        if (true === $flush) {
            $connection->beginTransaction();
            try {
                $connection->exec($this->insertSql);
                $connection->commit();
            } catch (\Throwable $e) {
                $connection->rollBack();
                throw $e;
            }

            $this->insertSql = '';
        }
    }
}
