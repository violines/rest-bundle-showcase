<?php

declare(strict_types=1);

namespace App\Infrastructure\Exception;

use App\Infrastructure\View\Error;
use Symfony\Component\HttpFoundation\Response;
use TerryApiBundle\Error\ErrorInterface;

class AuthorizationFailedException extends \RuntimeException implements \Throwable, ErrorInterface
{
    public static function roleMissing(): self
    {
        return new self('User has not the required access rights.');
    }

    public static function entryNotAllowed(): self
    {
        return new self('You are not allowed to enter this.');
    }

    public function getContent(): object
    {
        return Error::create($this->message);
    }

    public function getHTTPStatusCode(): int
    {
        return Response::HTTP_FORBIDDEN;
    }
}
