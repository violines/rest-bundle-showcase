<?php

declare(strict_types=1);

namespace App\Import\Model;

class Candy
{
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

    public static function new(
        string $gtin,
        int $weight,
        string $language,
        string $title
    ) {
        return new self($gtin, $weight, $language, $title);
    }

    public function toArray()
    {
        return [
            'gtin' => $this->gtin,
            'weight' => $this->weight,
            'language' => $this->language,
            'title' => $this->title
        ];
    }
}
