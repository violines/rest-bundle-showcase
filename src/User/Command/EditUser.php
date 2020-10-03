<?php

declare(strict_types=1);

namespace App\User\Command;

use TerryApiBundle\HttpApi\HttpApi;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @HttpApi
 */
class EditUser
{
    /**
     * @Assert\Type("int")
     */
    public $id;

    /**
     * @Assert\Type("string")
     */
    public $email;

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
