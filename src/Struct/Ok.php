<?php

declare(strict_types=1);

namespace App\Struct;

use TerryApiBundle\Annotation\Struct;

/**
 * @Struct
 */
class Ok
{
    public $message = "Everything is fine.";

    public static function create()
    {
        return new self();
    }
}
