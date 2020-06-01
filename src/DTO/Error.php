<?php

declare(strict_types=1);

namespace App\DTO;

use TerryApiBundle\Annotation\HTTPApi;

/**
 * @HTTPApi
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
