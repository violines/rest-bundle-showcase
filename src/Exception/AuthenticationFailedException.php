<?php

declare(strict_types=1);

namespace App\Exception;

use App\Struct\Error\HTTPAuthenticationFailed;
use Symfony\Component\HttpFoundation\Response;
use TerryApiBundle\Exception\HTTPErrorInterface;

class AuthenticationFailedException extends \RuntimeException implements \Throwable, HTTPErrorInterface
{
    public static function create(): self
    {
        return new self();
    }

    public function getStruct(): object
    {
        return HTTPAuthenticationFailed::create();
    }

    public function getHTTPStatusCode(): int
    {
        return Response::HTTP_UNAUTHORIZED;
    }
}
