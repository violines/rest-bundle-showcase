<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Catalog\Product;
use App\Domain\Catalog\Value\Gtin;
use App\Domain\Review\Review;
use App\Domain\Review\Repository\ReviewRepository;
use App\Domain\Review\Value\ReviewId;
use App\Domain\User\Value\UserId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method Review|null findOneBy(array $criteria, array $orderBy = null)
 * @method Review[]    findAll()
 * @method Review[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewDoctrineRepository implements ReviewRepository
{
    private ServiceEntityRepository $serviceEntityRepository;

    public function __construct(private Connection $connection, private EntityManagerInterface $entityManager, ManagerRegistry $registry)
    {
        $this->serviceEntityRepository = new ServiceEntityRepository($registry, Review::class);
    }

    public function nextId(): ReviewId
    {
        return ReviewId::fromInt((int)$this->connection->fetchColumn('SELECT setval(\'review_id_seq\', nextval(\'review_id_seq\'::regclass))'));
    }

    public function save(Review $review): void
    {
        $this->entityManager->persist($review);
        $this->entityManager->flush();
    }

    public function exists(Gtin $gtin, UserId $userId): bool
    {
        $result = $this->serviceEntityRepository->createQueryBuilder('rating')
            ->join(Product::class, 'product', 'WITH', 'product.id = rating.productId')
            ->andWhere('product.gtin = :gtin')
            ->andWhere('rating.userId = :userId')
            ->setParameter('gtin', $gtin->toString())
            ->setParameter('userId', $userId->toInt())
            ->getQuery()
            ->getOneOrNullResult();

        return null !== $result;
    }

    public function find(ReviewId $reviewId): Review
    {
        return $this->serviceEntityRepository->find($reviewId->toInt());
    }
}
