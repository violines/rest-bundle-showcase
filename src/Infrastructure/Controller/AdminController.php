<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Infrastructure\Exception\NotFoundException;
use App\User\Exception\UserNotExists;
use App\Infrastructure\View\Ok;
use App\User\Command\EditUser;
use App\User\UserService;
use App\User\View\User;
use Symfony\Component\Routing\Annotation\Route;

class AdminController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @Route("/admin/user/list", methods={"GET"}, name="admin_user_list")
     * @return User[]
     */
    public function userList(): array
    {
        return $this->userService->getUsers();
    }

    /**
     * @Route("/admin/user/edit", methods={"POST"}, name="admin_user_edit")
     */
    public function editUser(EditUser $editUser): Ok
    {
        try {
            $this->userService->editUser($editUser);
        } catch (UserNotExists $e) {
            throw NotFoundException::resource();
        }

        return Ok::create();
    }
}
