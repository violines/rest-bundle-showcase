<?php

declare(strict_types=1);

namespace App\Exception;

use App\Struct\Error\HTTPNotFound;
use Symfony\Component\HttpFoundation\Response;
use TerryApiBundle\Exception\HTTPErrorInterface;

class NotFoundException extends \LogicException implements \Throwable, HTTPErrorInterface
{
    public static function create(): self
    {
        return new self();
    }

    public function getStruct(): object
    {
        return HTTPNotFound::create();
    }

    public function getHTTPStatusCode(): int
    {
        return Response::HTTP_NOT_FOUND;
    }
}
