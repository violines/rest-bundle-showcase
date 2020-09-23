<?php

declare(strict_types=1);

namespace App\Product\View;

use TerryApiBundle\Annotation\HTTPApi;
use Symfony\Component\Serializer;

/**
 * @HTTPApi
 */
final class ProductView
{
    private string $gtin;

    private int $weight;

    private string $name;

    /**
     * @Serializer\Annotation\SerializedName("average_rating")
     */
    private int $averageRating;

    public function __construct(string $gtin, int $weight, string $name, int $averageRating)
    {
        $this->gtin = $gtin;
        $this->weight = $weight;
        $this->name = $name;
        $this->averageRating = $averageRating;
    }

    public function getGtin(): string
    {
        return $this->gtin;
    }

    public function getWeight(): int
    {
        return $this->weight;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAverageRating(): int
    {
        return $this->averageRating;
    }
}
