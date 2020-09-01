<?php

declare(strict_types=1);

namespace App\Entity;

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

    public function gtin()
    {
        return $this->gtin;
    }

    public function weight()
    {
        return $this->weight;
    }

    public function title(string $language): string
    {
        $name = $this->translations->filter(
            fn (CandyTranslation $t) => $t->isLanguage($language)
        )->first();

        return $name !== false ? $name->title() : '';
    }
}
