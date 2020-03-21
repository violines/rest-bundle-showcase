<?php

declare(strict_types=1);

namespace App\Struct\Frontend;

use TerryApiBundle\Annotation\Struct;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Struct
 */
class ProfileWrite
{
    /**
     * @Assert\Type("string")
     */
    public $email;

    /**
     * @Assert\Type("string")
     */
    public $password;

    /**
     * @Assert\Type("boolean")
     */
    public $isResetKey;
}
