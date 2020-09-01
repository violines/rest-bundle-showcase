<?php

declare(strict_types=1);

namespace App\View;

use TerryApiBundle\Annotation\HTTPApi;

/**
 * @HTTPApi
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
