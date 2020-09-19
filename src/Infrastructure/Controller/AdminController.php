<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Infrastructure\Exception\NotFoundException;
use App\Infrastructure\Repository\UserRepository;
use App\Infrastructure\View\Ok;
use App\User\Command\EditUser;
use App\User\View\User;
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
     * @return User[]
     */
    public function userList(): array
    {
        $users = [];

        foreach ($this->userRepository->findAll() as $user) {
            $users[] = User::fromEntity($user);
        }

        return $users;
    }

    /**
     * @Route("/admin/user/edit", methods={"POST"}, name="admin_user_edit")
     */
    public function editUser(EditUser $editUser): Ok
    {
        $user = $this->userRepository->findOneBy(['email' => $editUser->email]);

        if (null === $user) {
            throw NotFoundException::resource();
        }

        if ($editUser->isResetKey) {
            $user->resetKey();
        }

        $user->changeEmail($editUser->email);
        $user->changeRoles($editUser->roles);

        $this->userRepository->save($user);

        return Ok::create();
    }
}
