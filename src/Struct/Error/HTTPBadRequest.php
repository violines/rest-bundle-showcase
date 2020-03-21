<?php

declare(strict_types=1);

namespace App\Struct\Error;

use TerryApiBundle\Annotation\Struct;

/**
 * @Struct
 */
class HTTPBadRequest
{
    public $message;

    private function __construct(string $message)
    {
        $this->message = $message;
    }

    public static function create(string $message): self
    {
        return new self($message);
    }
}
