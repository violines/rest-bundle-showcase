<?php

namespace App\Repository;

use App\Entity\Candy;
use App\Entity\Review;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method Rating|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rating|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rating[]    findAll()
 * @method Rating[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $entityManager;

    private Connection $connection;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
        $this->connection = $this->getEntityManager()->getConnection();
        $this->entityManager = $this->getEntityManager();
    }

    public function nextId(): int
    {
        return $this->connection->transactional(function () {

            $nextId = (int)$this->connection->fetchColumn('SELECT "last_value" FROM "review_id_seq"') + 1;

            $statement = $this->connection->prepare('SELECT setval(\'review_id_seq\', :next_value)');
            $statement->bindValue('next_value', $nextId);
            $statement->execute();

            return $nextId;
        });
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

    public function save(Review $review)
    {
        $this->entityManager->persist($review);
        $this->entityManager->flush();
    }
}
