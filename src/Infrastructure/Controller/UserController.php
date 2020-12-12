<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Infrastructure\Exception\AuthenticationFailedException;
use App\Infrastructure\Exception\BadRequestException;
use App\Infrastructure\Exception\NotFoundException;
use App\User\Exception\UserNotExists;
use App\Infrastructure\View\Ok;
use App\User\Command\CreateProfile;
use App\User\Command\EditUser;
use App\User\Entity\User as UserEntity;
use App\User\Exception\UserAlreadyExists;
use App\User\UserService;
use App\User\Value\UserId;
use App\User\View\ProfileView;
use App\User\View\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class UserController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @return User[]
     */
    #[Route('/admin/users', methods: ['GET'], name:'admin_users')]
    public function findUsers(): array
    {
        return $this->userService->users();
    }

    #[Route('/admin/user', methods: ['POST'], name:'admin_edit_user')]
    public function editUser(EditUser $editUser): Ok
    {
        try {
            $this->userService->editUser($editUser);
        } catch (UserNotExists $e) {
            throw NotFoundException::resource();
        }

        return Ok::create();
    }

    #[Route('/{_locale}/register', methods: ['POST'], name:'frontend_register', requirements:['_locale' => 'en|de'])]
    public function register(CreateProfile $createProfile): Ok
    {
        try {
            $this->userService->createProfile($createProfile);
        } catch (UserAlreadyExists $e) {
            throw BadRequestException::userExists();
        }

        return Ok::create();
    }

    #[Route('/{_locale}/profile/{userId}', methods: ['GET'], name:'frontend_profile', requirements:['_locale' => 'en|de'])]
    public function profile(int $userId, UserInterface $user): ProfileView
    {
        if (!$user instanceof UserEntity || $user->getId() !== $userId) {
            throw AuthenticationFailedException::userNotFound();
        }

        return $this->userService->profile(UserId::fromInt($userId));
    }
}
