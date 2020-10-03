<?php

declare(strict_types=1);

namespace App\Product\View;

use TerryApiBundle\HttpApi\HttpApi;

/**
 * @HttpApi
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

    public function getKey(): string
    {
        return $this->key;
    }

    public function getSorting(): int
    {
        return $this->sorting;
    }
}
