<?php

namespace App\Domain\User\Repository;

use App\Domain\User\User;
use App\Domain\User\Value\Email;
use App\Domain\User\Value\UserId;

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
