<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\User\Command\CreateProfile;
use App\Domain\User\Command\EditUser;
use App\Domain\User\Entity\User;
use App\Domain\User\Exception\UserAlreadyExists;
use App\Domain\User\Exception\UserNotExists;
use App\Domain\User\PasswordEncoder;
use App\Domain\User\Repository\UserRepository;
use App\Domain\User\Repository\UserViewRepository;
use App\Domain\User\Value\Email;
use App\Domain\User\Value\UserId;
use App\Domain\User\View\ProfileView;
use App\Domain\User\View\UserView;

class UserService
{
    private UserRepository $userRepository;

    private UserViewRepository $userViewRepository;

    private PasswordEncoder $passwordEncoder;

    public function __construct(UserRepository $userRepository, UserViewRepository $userViewRepository, PasswordEncoder $passwordEncoder)
    {
        $this->userRepository = $userRepository;
        $this->userViewRepository = $userViewRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function createProfile(CreateProfile $createProfile): void
    {
        if ($this->userRepository->userExists(Email::fromString($createProfile->email))) {
            throw UserAlreadyExists::email($createProfile->email);
        }

        $userId = $this->userRepository->nextId();

        $this->userRepository->saveUser(User::fromCreateProfile($userId, $createProfile, $this->passwordEncoder));
    }

    public function profile(UserId $userId): ProfileView
    {
        return $this->userViewRepository->findProfileView($userId);
    }

    public function editUser(EditUser $editUser): void
    {
        $user = $this->userRepository->findUser(UserId::fromInt($editUser->id));

        if (null === $user) {
            throw UserNotExists::id($editUser->id);
        }

        if ($editUser->isResetKey) {
            $user->resetKey();
        }

        $user->changeEmail(Email::fromString($editUser->email));
        $user->changeRoles($editUser->roles);

        $this->userRepository->saveUser($user);
    }

    /**
     * @return UserView[]
     */
    public function users(): array
    {
        return $this->userViewRepository->findUserViews();
    }
}
