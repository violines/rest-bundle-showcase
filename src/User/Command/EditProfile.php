<?php

declare(strict_types=1);

namespace App\User\Command;

use Violines\RestBundle\HttpApi\HttpApi;
use Symfony\Component\Serializer;
use Symfony\Component\Validator\Constraints as Assert;

#[HttpApi]
class EditProfile
{
    #[Serializer\Annotation\SerializedName("user_id"), Assert\Type("int")]
    public $userId;

    #[Assert\Type("string")]
    public $email;

    #[Assert\Type("boolean")]
    public $isResetPassword;

    #[Assert\Type("boolean")]
    public $isResetKey;
}
