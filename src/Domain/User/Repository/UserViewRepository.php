<?php

namespace App\Domain\User\Repository;

use App\Domain\User\Value\UserId;
use App\Domain\User\View\ProfileView;
use App\Domain\User\View\UserView;

interface UserViewRepository
{
    /**
     * @param UserId $userId
     * @return UserView
     */
    public function findUserView(UserId $userId): UserView;

    /**
     * @return UserView[]
     */
    public function findUserViews(): array;

    /**
     * @param UserId $userId
     * @return ProfileView
     */
    public function findProfileView(UserId $userId): ProfileView;
}
