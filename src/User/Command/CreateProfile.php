<?php

declare(strict_types=1);

namespace App\User\Command;

use Violines\RestBundle\HttpApi\HttpApi;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @HttpApi
 */
class CreateProfile
{
    /**
     * @Assert\Type("string")
     */
    public $email;

    /**
     * @Assert\Type("string")
     */
    public $password;
}
