<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\User\Repository\UserViewRepository;
use App\Domain\User\Value\UserId;
use App\Domain\User\View\ProfileView;
use App\Domain\User\View\UserView;
use Doctrine\DBAL\Connection;

class UserViewSqlRepository implements UserViewRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function findUserView(UserId $userId): UserView
    {
        $statement = $this->connection->createQueryBuilder()
            ->select('"user".id, "user".email, "user".roles, "user".key')
            ->from('"user"')
            ->andWhere('"user".id = :userId')
            ->setParameter('userId', $userId->toInt())
            ->executeQuery();

        $user = $statement->fetchAssociative();

        return new UserView($user['id'], $user['email'], json_decode($user['roles']), $user['key']);
    }

    public function findUserViews(): array
    {
        $statement = $this->connection->createQueryBuilder()
            ->select('"user".id, "user".email, "user".roles, "user".key')
            ->from('"user"')
            ->executeQuery();

        $users = $statement->fetchAllAssociative();

        $userViews = [];

        foreach ($users  as $user) {
            $userViews[] =  new UserView($user['id'], $user['email'], json_decode($user['roles']), $user['key']);
        }

        return $userViews;
    }

    public function findProfileView(UserId $userId): ProfileView
    {
        $statement = $this->connection->createQueryBuilder()
            ->select('"user".id, "user".email, "user".key')
            ->from('"user"')
            ->andWhere('"user".id = :userId')
            ->setParameter('userId', $userId->toInt())
            ->executeQuery();

        $user = $statement->fetchAssociative();

        return new ProfileView($user['id'], $user['email'], $user['key']);
    }
}
