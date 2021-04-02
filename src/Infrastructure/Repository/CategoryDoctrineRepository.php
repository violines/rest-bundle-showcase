<?php

namespace App\Infrastructure\Repository;

use App\Domain\Product\Entity\Category;
use App\Domain\Product\Repository\CategoryRepository;
use App\Domain\Product\Value\CategoryId;
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
class CategoryDoctrineRepository extends ServiceEntityRepository implements CategoryRepository
{
    private EntityManagerInterface $em;

    private Connection $connection;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
        $this->connection = $this->getEntityManager()->getConnection();
        $this->em = $this->getEntityManager();
    }

    public function nextId(): CategoryId
    {
        return CategoryId::fromInt((int)$this->connection->fetchColumn('SELECT setval(\'category_id_seq\', nextval(\'category_id_seq\'::regclass))'));
    }

    public function saveCategory(Category $category): void
    {
        $this->em->persist($category);
        $this->em->flush();
    }

    public function categoryExists(string $key): bool
    {
        return null !== $this->findOneBy(['key' => $key]);
    }

    public function findCategory(CategoryId $categoryId): Category
    {
        return $this->find($categoryId->toInt());
    }
}
