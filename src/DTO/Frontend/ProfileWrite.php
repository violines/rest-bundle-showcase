<?php

declare(strict_types=1);

namespace App\DTO\Frontend;

use TerryApiBundle\Annotation\HTTPApi;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @HTTPApi
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
