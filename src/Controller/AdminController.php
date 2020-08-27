<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\NotFoundException;
use App\Repository\UserRepository;
use App\DTO\Admin\User as AdminUser;
use Symfony\Component\Routing\Annotation\Route;

class AdminController
{
    private UserRepository $userRepository;

    public function __construct(
        UserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/admin/user/list", methods={"GET"}, name="admin_user_list")
     * @return AdminUser[]
     */
    public function userList(): array
    {
        $users = [];

        foreach ($this->userRepository->findAll() as $user) {
            $users[] = $user->toAdminDTO();
        }

        return $users;
    }

    /**
     * @Route("/admin/user/edit", methods={"POST"}, name="admin_user_edit")
     */
    public function editUser(AdminUser $adminUser): AdminUser
    {
        $user = $this->userRepository->findOneBy(['email' => $adminUser->email]);

        if (null === $user) {
            throw NotFoundException::resource();
        }

        $user->adminEdit($adminUser);

        $this->userRepository->save($user);

        return $user->toAdminDTO();
    }
}
