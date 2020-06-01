<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\NotFoundException;
use App\Repository\UserRepository;
use App\DTO\Admin\User as AdminUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdminController
{
    private EntityManagerInterface $entityManager;

    private UserRepository $userRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository
    ) {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }
    /**
     * @Route("/admin/user/list", methods={"GET"}, name="admin_user_list")
     * @return AdminUser[]
     */
    public function userList(): array
    {
        $_users = [];

        foreach ($this->userRepository->findAll() as $user) {
            $_users[] = $user->toAdminUser();
        }

        return $_users;
    }

    /**
     * @Route("/admin/user/edit", methods={"POST"}, name="admin_user_edit")
     */
    public function editUser(AdminUser $dto): AdminUser
    {
        $user = $this->userRepository->findOneBy(['email' => $dto->email]);

        if (null === $user) {
            throw NotFoundException::resource();
        }

        if ($dto->isResetKey) {
            $user->resetKey();
        }

        $user->addRoles($dto->roles);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user->toAdminUser();
    }
}
