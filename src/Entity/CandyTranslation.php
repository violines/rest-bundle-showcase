<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CandyTranslationRepository")
 * @Table(
 *      uniqueConstraints={@UniqueConstraint(name="uq_candy_id_idx", columns={"candy_id","language"})}
 * )
 */
class CandyTranslation
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer", options={"default"="nextval('candy_translation_id_seq'::regclass)"})
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\SequenceGenerator(sequenceName="candy_translation_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $language;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Candy", inversedBy="translations")
     */
    private $candy;

    public function title(): string
    {
        return $this->title;
    }

    public function isLanguage(string $language): bool
    {
        return $this->language === $language;
    }
}
