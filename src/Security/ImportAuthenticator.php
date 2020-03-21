<?php

namespace App\Security;

use App\Exception\AuthenticationFailedException;
use App\Exception\AuthorizationFailedException;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class ImportAuthenticator extends AbstractGuardAuthenticator
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function supports(Request $request): bool
    {
        return $request->headers->has('X-AUTH-TOKEN');
    }

    public function getCredentials(Request $request): string
    {
        return $request->headers->get('X-AUTH-TOKEN', '');
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        if ('' === $credentials) {
            return null;
        }

        return $this->userRepository->findOneBy(['key' => $credentials]);
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        if (!in_array('ROLE_IMPORT', $user->getRoles())) {
            throw AuthorizationFailedException::roleMissing();
        }

        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        throw AuthenticationFailedException::userNotFound();
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        throw AuthenticationFailedException::userNotFound();
    }

    public function supportsRememberMe(): bool
    {
        return false;
    }
}
