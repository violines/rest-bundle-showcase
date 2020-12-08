<?php

declare(strict_types=1);

namespace App\Infrastructure\View;

use Violines\RestBundle\HttpApi\HttpApi;

/**
 * @HttpApi
 */
final class Ok
{
    private string $message = "Everything is fine.";

    public static function create()
    {
        return new self();
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
