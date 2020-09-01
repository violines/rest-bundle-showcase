<?php

declare(strict_types=1);

namespace App\View;

use App\Entity\Candy as CandyEntity;
use TerryApiBundle\Annotation\HTTPApi;
use Symfony\Component\Serializer;

/**
 * @HTTPApi
 */
final class Candy
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

    public static function fromEntity(CandyEntity $candy, string $language, ?array $averageRating = null): self
    {
        return new self($candy->gtin(), $candy->weight(), $candy->title($language), $averageRating);
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
