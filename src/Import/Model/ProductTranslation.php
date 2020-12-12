<?php

declare(strict_types=1);

namespace App\Import\Model;

use Violines\RestBundle\HttpApi\HttpApi;
use Symfony\Component\Validator\Constraints as Assert;

#[HttpApi]
class ProductTranslation
{
    /**
     * @var string
     */
    #[Assert\Type("string")]
    public $language;

    /**
     * @var string
     */
    #[Assert\Type("string")]
    public $title;

    public function toArray()
    {
        return [
            'language' => $this->language,
            'title' => $this->title
        ];
    }
}
