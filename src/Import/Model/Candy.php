<?php

declare(strict_types=1);

namespace App\Import\Model;

use App\DTO\Import\Candy as ImportCandy;
use App\DTO\Import\ImportCandyTranslation as ImportCandyTranslation;

class Candy
{
    public const PROPERTY_AMOUNT = 4;

    private string $gtin;

    private int $weight;

    private string $language;

    private string $title;

    private function __construct(
        string $gtin,
        int $weight,
        string $language,
        string $title
    ) {
        $this->gtin = $gtin;
        $this->weight = $weight;
        $this->language = $language;
        $this->title = $title;
    }

    public static function fromImportDTO(ImportCandy $importCandy): array
    {
        $_candies = [];

        /** @var ImportCandyTranslation $translation */
        foreach ($importCandy->translations as $translation) {
            $_candies[] = new self(
                $importCandy->gtin,
                $importCandy->weight,
                $translation->language,
                $translation->title,
            );
        }

        return $_candies;
    }

    public function getGtin(): string
    {
        return $this->gtin;
    }

    public function getWeight(): int
    {
        return $this->weight;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}
