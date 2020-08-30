<?php

declare(strict_types=1);

namespace App\Import\HTTPApi;

use App\Import\Model\Candy as ImportCandy;
use TerryApiBundle\Annotation\HTTPApi;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @HTTPApi
 */
class Candy
{
    /**
     * @Assert\Type("string")
     * @var string
     */
    public $gtin;

    /**
     * @Assert\Type("int")
     * @var int
     */
    public $weight;

    /**
     * @Assert\Type("array")
     * @var CandyTranslation[]
     */
    public $translations;

    /**
     * @return ImportCandy[]
     */
    public function toImport(): array
    {
        $candies = [];

        foreach ($this->translations as $translation) {
            $candies[] = ImportCandy::new(
                $this->gtin,
                $this->weight,
                $translation->language,
                $translation->title,
            );
        }

        return $candies;
    }
}
