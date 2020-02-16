<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\CandyTranslation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method CandyTranslation|null find($id, $lockMode = null, $lockVersion = null)
 * @method CandyTranslation|null findOneBy(array $criteria, array $orderBy = null)
 * @method CandyTranslation[]    findAll()
 * @method CandyTranslation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CandyTranslationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CandyTranslation::class);
    }
}
