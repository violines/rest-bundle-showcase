<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Category\Category;
use App\Domain\Category\Repository\CategoryRepository;
use App\Domain\Category\Value\CategoryId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryDoctrineRepository implements CategoryRepository
{
    private Connection $connection;

    private EntityManagerInterface $em;

    private ServiceEntityRepository $serviceEntityRepository;

    public function __construct(ManagerRegistry $registry)
    {
        $this->serviceEntityRepository = new ServiceEntityRepository($registry, Category::class);
        $this->connection = $this->serviceEntityRepository->getEntityManager()->getConnection();
        $this->em = $this->serviceEntityRepository->getEntityManager();
    }

    public function nextId(): CategoryId
    {
        return CategoryId::fromInt((int)$this->connection->fetchColumn('SELECT setval(\'category_id_seq\', nextval(\'category_id_seq\'::regclass))'));
    }

    public function save(Category $category): void
    {
        $this->em->persist($category);
        $this->em->flush();
    }

    public function exists(string $key): bool
    {
        return null !== $this->serviceEntityRepository->findOneBy(['key' => $key]);
    }

    public function find(CategoryId $categoryId): Category
    {
        return $this->serviceEntityRepository->find($categoryId->toInt());
    }
}
