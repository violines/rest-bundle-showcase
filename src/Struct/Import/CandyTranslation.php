<?php

declare(strict_types=1);

namespace App\Struct\Import;

use TerryApiBundle\Annotation\Struct;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Struct
 */
class CandyTranslation
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
}
