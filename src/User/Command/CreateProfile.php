<?php

declare(strict_types=1);

namespace App\User\Command;

use TerryApiBundle\Annotation\HTTPApi;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @HTTPApi
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
