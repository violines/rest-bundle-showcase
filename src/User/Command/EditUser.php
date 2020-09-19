<?php

declare(strict_types=1);

namespace App\User\Command;

use TerryApiBundle\Annotation\HTTPApi;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @HTTPApi
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
