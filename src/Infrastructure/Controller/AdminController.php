<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Exception\NotFoundException;
use App\CommandObject\User;
use App\Infrastructure\Repository\UserRepository;
use App\View\Ok as OkView;
use App\View\User as UserView;
use Symfony\Component\Routing\Annotation\Route;

class AdminController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
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
            $users[] = UserView::fromEntity($user);
        }

        return $users;
    }

    /**
     * @Route("/admin/user/edit", methods={"POST"}, name="admin_user_edit")
     */
    public function editUser(User $adminUser): OkView
    {
        $user = $this->userRepository->findOneBy(['email' => $adminUser->email]);

        if (null === $user) {
            throw NotFoundException::resource();
        }

        if ($adminUser->isResetKey) {
            $user->resetKey();
        }
        $user->changeEmail($adminUser->email);
        $user->changeRoles($adminUser->roles);

        $this->userRepository->save($user);

        return OkView::create();
    }
}
