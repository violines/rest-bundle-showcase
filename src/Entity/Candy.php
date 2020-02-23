<?php

declare(strict_types=1);

namespace App\Entity;

use App\Struct\Frontend\Candy as FrontendStruct;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CandyRepository")
 */
class Candy
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer", options={"default"="nextval('candy_id_seq'::regclass)"})
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\SequenceGenerator(sequenceName="candy_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $gtin;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $weight;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CandyTranslation", mappedBy="candy", cascade={"persist"})
     */
    private $translations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Review", mappedBy="candy")
     */
    private $reviews;

    public function __construct(int $weight, PersistentCollection $translations)
    {
        $this->weight = $weight;
        $this->translations = $translations;
    }

    public function toFrontendStruct(string $language, ?array $averageRating = null): FrontendStruct
    {
        return new FrontendStruct(
            $this->gtin,
            $this->weight,
            $this->translationByLanguage($language),
            $averageRating
        );
    }

    private function translationByLanguage(string $language): string
    {
        $name = $this->translations->filter(
            fn (CandyTranslation $t) => $t->isLanguage($language)
        )->first();

        return $name !== false ? $name->getTitle() : '';
    }
}
