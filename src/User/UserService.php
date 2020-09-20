<?php

declare(strict_types=1);

namespace App\User;

use App\User\Command\CreateProfile;
use App\User\Command\EditUser;
use App\User\Entity\User;
use App\User\Exception\UserAlreadyExists;
use App\User\Exception\UserNotExists;
use App\User\PasswordEncoder;
use App\User\Repository\UserRepository;
use App\User\View\ProfileView;
use App\User\View\UserView;

class UserService
{
    private UserRepository $userRepository;

    private PasswordEncoder $passwordEncoder;

    public function __construct(UserRepository $userRepository, PasswordEncoder $passwordEncoder)
    {
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function createProfile(CreateProfile $createProfile): void
    {
        if ($this->userRepository->userExists($createProfile->email)) {
            throw UserAlreadyExists::email($createProfile->email);
        }

        $this->userRepository->saveUser(User::fromCreateProfile($createProfile, $this->passwordEncoder));
    }

    public function getProfile(User $user): ProfileView
    {
        return ProfileView::fromEntity($user);
    }

    public function editUser(EditUser $editUser): void
    {
        $user = $this->userRepository->findUser($editUser->id);

        if (null === $user) {
            throw UserNotExists::id($editUser->id);
        }

        if ($editUser->isResetKey) {
            $user->resetKey();
        }

        $user->changeEmail($editUser->email);
        $user->changeRoles($editUser->roles);

        $this->userRepository->saveUser($user);
    }

    /**
     * @return UserView[]
     */
    public function getUsers(): array
    {
        $users = [];

        foreach ($this->userRepository->findUsers() as $user) {
            $users[] = UserView::fromEntity($user);
        }

        return $users;
    }
}
