<?php

declare(strict_types=1);

namespace App\User\Command;

use Violines\RestBundle\HttpApi\HttpApi;
use Symfony\Component\Validator\Constraints as Assert;

#[HttpApi]
class CreateProfile
{
    #[Assert\Type("string")]
    #[Assert\Email]
    #[Assert\NotNull]
    public $email;

    #[Assert\Type("string")]
    #[Assert\NotNull]
    public $password;
}
