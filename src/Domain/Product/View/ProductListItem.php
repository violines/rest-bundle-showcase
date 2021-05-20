<?php

declare(strict_types=1);

namespace App\Domain\Product\View;

use Violines\RestBundle\HttpApi\HttpApi;
use Symfony\Component\Serializer;

#[HttpApi]
final class ProductListItem
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
