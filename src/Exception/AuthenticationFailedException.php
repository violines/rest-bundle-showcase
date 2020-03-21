<?php

declare(strict_types=1);

namespace App\Exception;

use App\Struct\Error;
use Symfony\Component\HttpFoundation\Response;
use TerryApiBundle\Exception\HTTPErrorInterface;

class AuthenticationFailedException extends \RuntimeException implements \Throwable, HTTPErrorInterface
{
    public static function userNotFound(): self
    {
        return new self('User not found.');
    }

    public function getStruct(): object
    {
        return Error::create($this->message);
    }

    public function getHTTPStatusCode(): int
    {
        return Response::HTTP_UNAUTHORIZED;
    }
}
