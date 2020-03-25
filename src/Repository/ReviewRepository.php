<?php

namespace App\Repository;

use App\Entity\Candy;
use App\Entity\Review;
use App\Entity\User;
use App\Exception\PersistenceLayerException;
use App\Import\Model\Review as ReviewModel;
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
    private const MAX_INSERT = 1000;

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

    /**
     * @param ReviewModel[] $candies
     */
    public function insert(array $reviews): void
    {
        $rowCount = count($reviews);

        if (0 === $rowCount) {
            return;
        }

        if (self::MAX_INSERT < $rowCount) {
            throw PersistenceLayerException::fromMaxInsert(self::MAX_INSERT, $rowCount);
        }

        $connection = $this->getEntityManager()->getConnection();

        $insertSql = '';

        /** @var ReviewModel $review */
        foreach ($reviews as $review) {
            // this uses the DBAL SQL QueryBuilder not the ORM's DQL builder
            $insertSql .= $connection->createQueryBuilder()
                ->insert('review')
                ->values($review->toArray())
                ->getSQL() . ';';
        }

        $connection->beginTransaction();

        try {
            $connection->exec($insertSql);
            $connection->commit();
        } catch (\Throwable $e) {
            $connection->rollBack();
            throw $e;
        }
    }

    public function findOneByGtinAndUser(string $gtin, User $user): ?Review
    {
        $result = $this->createQueryBuilder('rating')
            ->join(Candy::class, 'candy', 'WITH', 'candy.id = rating.candy')
            ->andWhere('candy.gtin = :gtin')
            ->andWhere('rating.user = :user')
            ->setParameter('gtin', $gtin)
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();

        if ([] === $result) {
            return null;
        }

        return current($result);
    }
}
