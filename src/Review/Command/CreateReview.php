<?php

declare(strict_types=1);

namespace App\Review\Command;

use Violines\RestBundle\HttpApi\HttpApi;
use Symfony\Component\Validator\Constraints as Assert;

#[HttpApi(requestInfoSource: HttpApi::BODY)] 
class CreateReview
{
    #[Assert\Type("int")]
    public $productId;

    #[Assert\IsNull]
    public $userId;

    #[Assert\Type("string")]
    public $gtin;

    #[Assert\Type("int")]
    #[Assert\GreaterThanOrEqual(1)]
    #[Assert\LessThanOrEqual(5)]
    public $taste;

    #[Assert\Type("int")]
    #[Assert\GreaterThanOrEqual(1)]
    #[Assert\LessThanOrEqual(5)]
    public $ingredients;

    #[Assert\Type("int")]
    #[Assert\GreaterThanOrEqual(1)]
    #[Assert\LessThanOrEqual(5)]
    public $healthiness;

    #[Assert\Type("int")]
    #[Assert\GreaterThanOrEqual(1)]
    #[Assert\LessThanOrEqual(5)]
    public $packaging;

    #[Assert\Type("int")]
    #[Assert\GreaterThanOrEqual(1)]
    #[Assert\LessThanOrEqual(5)]
    public $availability;

    #[Assert\Type("string")]
    public $comment;
}
