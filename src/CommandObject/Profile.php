<?php

declare(strict_types=1);

namespace App\CommandObject;

use TerryApiBundle\Annotation\HTTPApi;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @HTTPApi
 */
class Profile
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
