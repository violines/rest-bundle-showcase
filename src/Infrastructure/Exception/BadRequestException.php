<?php

declare(strict_types=1);

namespace App\Infrastructure\Exception;

use App\Infrastructure\View\Error;
use Symfony\Component\HttpFoundation\Response;
use Violines\RestBundle\Error\ErrorInterface;

class BadRequestException extends \LogicException implements \Throwable, ErrorInterface
{
    public static function userExists(): self
    {
        return new self('User already exists');
    }

    public static function reviewExists(): self
    {
        return new self('Review already exists');
    }

    public function getContent(): object
    {
        return Error::create($this->message);
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }
}
