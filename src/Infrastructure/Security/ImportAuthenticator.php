<?php

declare(strict_types=1);

namespace App\Infrastructure\Security;

use App\Infrastructure\Exception\AuthenticationFailedException;
use App\Infrastructure\Repository\UserDoctrineRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class ImportAuthenticator extends AbstractAuthenticator
{
    private UserDoctrineRepository $userRepository;

    public function __construct(UserDoctrineRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function supports(Request $request): bool
    {
        return $request->headers->has('X-AUTH-TOKEN');
    }

    public function authenticate(Request $request): Passport
    {
        $apiToken = $request->headers->get('X-AUTH-TOKEN');
        if (null === $apiToken) {
            throw AuthenticationFailedException::userNotFound();
        }

        return new SelfValidatingPassport(
            new UserBadge($apiToken, function ($userIdentifier) {
                return $this->userRepository->findOneBy(['key' => $userIdentifier]);
            })
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        throw AuthenticationFailedException::userNotFound();
    }
}
