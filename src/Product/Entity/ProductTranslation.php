<?php

declare(strict_types=1);

namespace App\Product\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @ORM\Entity(repositoryClass="App\Infrastructure\Repository\ProductTranslationDoctrineRepository")
 * @Table(
 *      uniqueConstraints={@UniqueConstraint(name="uq_product_id_idx", columns={"product_id","language"})}
 * )
 */
class ProductTranslation
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer", options={"default"="nextval('product_translation_id_seq'::regclass)"})
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\SequenceGenerator(sequenceName="product_translation_id_seq", allocationSize=1, initialValue=1)
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
     * @ORM\ManyToOne(targetEntity="App\Product\Entity\Product", inversedBy="translations")
     */
    private $product;

    public function title(): string
    {
        return $this->title;
    }

    public function isLanguage(string $language): bool
    {
        return $this->language === $language;
    }
}
