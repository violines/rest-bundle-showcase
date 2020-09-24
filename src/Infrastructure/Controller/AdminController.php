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
     * @Route("/admin/users", methods={"GET"}, name="admin_users")
     * @return User[]
     */
    public function users(): array
    {
        return $this->userService->users();
    }

    /**
     * @Route("/admin/user", methods={"POST"}, name="admin_edit_user")
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
