<?php

namespace App\User\Repository;

use App\User\Entity\User;

interface UserRepository
{
    /**
     * @param User $user
     */
    public function saveUser(User $user): void;

    /**
     * @param string $email
     * @return bool
     */
    public function userExists(string $email): bool;

    /**
     * @param int $id
     * @return User
     */
    public function findUser(int $id): User;

    /**
     * @return User[]
     */
    public function findUsers(): array;
}
