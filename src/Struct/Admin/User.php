<?php

declare(strict_types=1);

namespace App\Struct\Admin;

use TerryApiBundle\Annotation\Struct;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Struct
 */
class User
{
    /**
     * @Assert\Type("string")
     */
    public $email;

    /**
     * @Assert\Type("string")
     */
    public $key;

    /**
     * @Assert\Type("boolean")
     */
    public $isResetPassword;

    /**
     * @Assert\Type("boolean")
     */
    public $isResetKey;

    /**
     * @Assert\Type("array")
     */
    public $roles;
}
