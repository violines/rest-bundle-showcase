<?php

namespace App\User\Repository;

use App\User\Entity\User;
use App\User\Value\Email;
use App\User\Value\UserId;

interface UserRepository
{
    /**
     * @return UserId
     */
    public function nextId(): UserId;

    /**
     * @param User $user
     */
    public function saveUser(User $user): void;

    /**
     * @param string $email
     * @return bool
     */
    public function userExists(Email $email): bool;

    /**
     * @param UserId $userId
     * @return User
     */
    public function findUser(UserId $userId): User;
}
