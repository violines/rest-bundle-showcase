<?php

declare(strict_types=1);

namespace App\Infrastructure\View;

use TerryApiBundle\Annotation\HTTPApi;

/**
 * @HTTPApi
 */
final class Error
{
    private string $message;

    private function __construct(string $message)
    {
        $this->message = $message;
    }

    public static function create(string $message): self
    {
        return new self($message);
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
