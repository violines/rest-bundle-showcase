<?php

declare(strict_types=1);

namespace App\Struct;

use TerryApiBundle\Annotation\Struct;

/**
 * @Struct
 */
class Error
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
