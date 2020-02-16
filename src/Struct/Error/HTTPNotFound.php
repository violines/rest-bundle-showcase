<?php

declare(strict_types=1);

namespace App\Struct\Error;

use TerryApiBundle\Annotation\Struct;

/**
 * @Struct
 */
class HTTPNotFound
{
    public $message = 'The requested resource was not found';

    public static function create(): self
    {
        return new self();
    }
}
