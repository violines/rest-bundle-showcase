<?php

declare(strict_types=1);

namespace App\Infrastructure\Exception;

use App\Infrastructure\View\Error;
use Symfony\Component\HttpFoundation\Response;
use TerryApiBundle\Error\ErrorInterface;

class AuthenticationFailedException extends \RuntimeException implements \Throwable, ErrorInterface
{
    public static function userNotFound(): self
    {
        return new self('User not found.');
    }

    public function getContent(): object
    {
        return Error::create($this->message);
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_UNAUTHORIZED;
    }
}
