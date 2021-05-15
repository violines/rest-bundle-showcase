<?php

declare(strict_types=1);

namespace App\Domain\Product\View;

use App\Domain\Product\Value\Language;
use Violines\RestBundle\HttpApi\HttpApi;
use Symfony\Component\Serializer;

#[HttpApi]
final class ProductListItem
{
    private string $language = 'en';

    private string $gtin;

    private int $weight;

    private array $name;

    /**
     * @Serializer\Annotation\SerializedName("average_rating")
     */
    private int $averageRating;

    public function __construct(string $gtin, int $weight, array $name, int $averageRating)
    {
        $this->gtin = $gtin;
        $this->weight = $weight;
        $this->name = $name;
        $this->averageRating = $averageRating;
    }

    public function withLanguage(Language $language): static
    {
        $this->language = $language->toString();

        return $this;
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
        return $this->name[$this->language] ?? '';
    }

    public function getAverageRating(): int
    {
        return $this->averageRating;
    }
}
