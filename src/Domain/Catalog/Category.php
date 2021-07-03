<?php

namespace App\Domain\Catalog;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Doctrine\ORM\Mapping\Table;

/**
 * @ORM\Entity(repositoryClass="App\Infrastructure\Repository\CategoryDoctrineRepository")
 * @Table(
 *      uniqueConstraints={@UniqueConstraint(name="uq_key_idx", columns={"key"})}
 * )
 */
class Category
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer", options={"default"="nextval('category_id_seq'::regclass)"})
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\SequenceGenerator(sequenceName="category_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $key;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sorting;
}
