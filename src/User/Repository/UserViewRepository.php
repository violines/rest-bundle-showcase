<?php

namespace App\User\Repository;

use App\User\Value\UserId;
use App\User\View\ProfileView;
use App\User\View\UserView;

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
