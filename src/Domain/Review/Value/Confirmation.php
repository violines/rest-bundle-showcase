<?php

declare(strict_types=1);

namespace App\Domain\Review\Value;

final class Confirmation
{
    public $email;

    public $headline;

    public $message;

    public function __construct(string $email, $headline, $message)
    {
        $this->email = $email;
        $this->headline = $headline;
        $this->message = $message;
    }
}
