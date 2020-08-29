<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Candy;
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
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Candy::class);
    }
}
