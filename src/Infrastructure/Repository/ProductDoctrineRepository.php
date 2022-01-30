<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Catalog\Product;
use App\Domain\Catalog\Repository\ProductRepository;
use App\Domain\Catalog\Value\Gtin;
use App\Domain\Catalog\Value\ProductId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductDoctrineRepository implements ProductRepository
{
    private Connection $connection;

    private EntityManagerInterface $em;

    private ServiceEntityRepository $serviceEntityRepository;

    public function __construct(ManagerRegistry $registry)
    {
        $this->serviceEntityRepository = new ServiceEntityRepository($registry, Product::class);
        $this->connection = $this->serviceEntityRepository->getEntityManager()->getConnection();
        $this->em = $this->serviceEntityRepository->getEntityManager();
    }

    public function nextId(): ProductId
    {
        return ProductId::fromInt((int)$this->connection->fetchFirstColumn('SELECT setval(\'product_id_seq\', nextval(\'product_id_seq\'::regclass))'));
    }

    public function save(Product $product): void
    {
        $this->em->persist($product);
        $this->em->flush();
    }

    public function exists(Gtin $gtin): bool
    {
        return null !== $this->serviceEntityRepository->findOneBy(['gtin' => $gtin->toString()]);
    }

    public function find(ProductId $productId): Product
    {
        return $this->serviceEntityRepository->find($productId->toInt());
    }
}
