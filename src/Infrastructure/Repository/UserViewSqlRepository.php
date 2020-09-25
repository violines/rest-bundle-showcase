<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\User\Repository\UserViewRepository;
use App\User\Value\UserId;
use App\User\View\ProfileView;
use App\User\View\UserView;
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
            ->execute();

        $user = $statement->fetch();

        return new UserView($user['id'], $user['email'], json_decode($user['roles']), $user['key']);
    }

    public function findUserViews(): array
    {
        $statement = $this->connection->createQueryBuilder()
            ->select('"user".id, "user".email, "user".roles, "user".key')
            ->from('"user"')
            ->execute();

        $users = $statement->fetchAll();

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
            ->execute();

        $user = $statement->fetch();

        return new ProfileView($user['id'], $user['email'], $user['key']);
    }
}
