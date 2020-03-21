<?php

declare(strict_types=1);

namespace App\Exception;

use App\Struct\Error\HTTPBadRequest;
use Symfony\Component\HttpFoundation\Response;
use TerryApiBundle\Exception\HTTPErrorInterface;

class BadRequestException extends \LogicException implements \Throwable, HTTPErrorInterface
{
    public static function userExists(): self
    {
        return new self('User already exists');
    }

    public function getStruct(): object
    {
        return HTTPBadRequest::create($this->message);
    }

    public function getHTTPStatusCode(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }
}
