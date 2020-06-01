<?php

declare(strict_types=1);

namespace App\Entity;

use App\DTO\Frontend\Candy as FrontendCandy;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Doctrine\ORM\Mapping\Table;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CandyRepository")
 * @Table(
 *      uniqueConstraints={@UniqueConstraint(name="uq_gtin_idx", columns={"gtin"})}
 * )
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

    public function toFrontendDTO(string $language, ?array $averageRating = null): FrontendCandy
    {
        return new FrontendCandy(
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
