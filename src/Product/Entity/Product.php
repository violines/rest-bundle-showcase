<?php

declare(strict_types=1);

namespace App\Product\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Doctrine\ORM\Mapping\Table;

/**
 * @ORM\Entity(repositoryClass="App\Infrastructure\Repository\ProductDoctrineRepository")
 * @Table(
 *      uniqueConstraints={@UniqueConstraint(name="uq_gtin_idx", columns={"gtin"})}
 * )
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer", options={"default"="nextval('product_id_seq'::regclass)"})
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\SequenceGenerator(sequenceName="product_id_seq", allocationSize=1, initialValue=1)
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
     * @ORM\OneToMany(targetEntity="App\Product\Entity\ProductTranslation", mappedBy="product", cascade={"persist"})
     */
    private $translations;
}
