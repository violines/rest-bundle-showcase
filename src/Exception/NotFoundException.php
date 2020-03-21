<?php

declare(strict_types=1);

namespace App\Exception;

use App\Struct\Error;
use Symfony\Component\HttpFoundation\Response;
use TerryApiBundle\Exception\HTTPErrorInterface;

class NotFoundException extends \LogicException implements \Throwable, HTTPErrorInterface
{
    public static function resource(): self
    {
        return new self('The requested resource was not found');
    }

    public function getStruct(): object
    {
        return Error::create($this->message);
    }

    public function getHTTPStatusCode(): int
    {
        return Response::HTTP_NOT_FOUND;
    }
}
