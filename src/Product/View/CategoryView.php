<?php

declare(strict_types=1);

namespace App\Product\View;

use App\Product\Entity\Category;
use TerryApiBundle\Annotation\HTTPApi;

/**
 * @HTTPApi
 */
final class CategoryView
{
    private string $key;

    private int $sorting;

    public function __construct(string $key, int $sorting)
    {
        $this->key = $key;
        $this->sorting = $sorting;
    }

    public static function fromEntity(Category $category): self
    {
        return new self($category->getKey(), $category->getSorting());
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getSorting(): int
    {
        return $this->sorting;
    }
}
