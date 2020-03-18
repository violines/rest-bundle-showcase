<?php

declare(strict_types=1);

namespace App\Struct\Error;

use TerryApiBundle\Annotation\Struct;

/**
 * @Struct
 */
class HTTPAuthorizationFailed
{
    public $message = 'User has not the required access rights.';

    public static function create(): self
    {
        return new self();
    }
}
