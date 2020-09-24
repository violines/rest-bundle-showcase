<?php

namespace App\Infrastructure\Repository;

use App\User\Entity\User;
use App\User\Repository\UserRepository;
use App\User\Value\UserId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserDoctrineRepository extends ServiceEntityRepository implements UserRepository
{
    private EntityManagerInterface $em;

    private Connection $connection;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
        $this->connection = $this->getEntityManager()->getConnection();
        $this->em = $this->getEntityManager();
    }

    public function nextId(): UserId
    {
        return UserId::fromInt((int)$this->connection->fetchColumn('SELECT setval(\'user_id_seq\', nextval(\'user_id_seq\'::regclass))'));
    }

    public function saveUser(User $user): void
    {
        $this->em->persist($user);
        $this->em->flush();
    }

    public function userExists(string $email): bool
    {
        return null !== $this->findOneBy(['email' => $email]);
    }

    public function findUser(int $id): User
    {
        return $this->find($id);
    }

    public function findUsers(): array
    {
        return $this->findAll();
    }
}
