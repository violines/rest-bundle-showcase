<?php

declare(strict_types=1);

namespace App\Infrastructure\Exception;

use App\View\Error;
use Symfony\Component\HttpFoundation\Response;
use TerryApiBundle\Exception\HTTPErrorInterface;

class BadRequestException extends \LogicException implements \Throwable, HTTPErrorInterface
{
    public static function userExists(): self
    {
        return new self('User already exists');
    }

    public function getContent(): object
    {
        return Error::create($this->message);
    }

    public function getHTTPStatusCode(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }
}
