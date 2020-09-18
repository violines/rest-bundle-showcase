<?php

declare(strict_types=1);

namespace App\Import\Model;

use TerryApiBundle\Annotation\HTTPApi;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @HTTPApi
 */
class ProductTranslation
{
    /**
     * @Assert\Type("string")
     * @var string
     */
    public $language;

    /**
     * @Assert\Type("string")
     * @var string
     */
    public $title;

    public function toArray()
    {
        return [
            'language' => $this->language,
            'title' => $this->title
        ];
    }
}
