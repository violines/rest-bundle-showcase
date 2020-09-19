<?php

declare(strict_types=1);

namespace App\Product\View;

use App\Product\Entity\Product as ProductEntity;
use TerryApiBundle\Annotation\HTTPApi;
use Symfony\Component\Serializer;

/**
 * @HTTPApi
 */
final class Product
{
    private string $gtin;

    private int $weight;

    private string $name;

    /**
     * @Serializer\Annotation\SerializedName("average_rating")
     */
    private ?array $averageRating;

    public function __construct(string $gtin, int $weight, string $name, ?array $averageRating)
    {
        $this->gtin = $gtin;
        $this->weight = $weight;
        $this->name = $name;
        $this->averageRating = $averageRating;
    }

    public static function fromEntity(ProductEntity $product, string $language, ?array $averageRating = null): self
    {
        return new self($product->gtin(), $product->weight(), $product->title($language), $averageRating);
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

    public function getAverageRating(): ?array
    {
        return $this->averageRating;
    }
}
