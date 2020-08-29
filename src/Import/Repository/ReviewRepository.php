<?php

namespace App\Import\Repository;

use App\Exception\PersistenceLayerException;
use App\Import\Model\Review;
use Doctrine\ORM\EntityManagerInterface;

class ReviewRepository
{
    private const MAX_INSERT = 1000;

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Review[] $candies
     */
    public function saveMany(array $reviews): void
    {
        $rowCount = count($reviews);

        if (0 === $rowCount) {
            return;
        }

        if (self::MAX_INSERT < $rowCount) {
            throw PersistenceLayerException::fromMaxInsert(self::MAX_INSERT, $rowCount);
        }

        $connection = $this->entityManager->getConnection();

        $insertSql = '';

        /** @var Review $review */
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
}
