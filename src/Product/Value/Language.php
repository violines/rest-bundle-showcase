<?php

namespace App\Product\Value;

final class Language
{
    private string $language;

    private function __construct(string $language)
    {
        $this->language = $language;
    }

    public static function fromString(string $language)
    {
        return new self($language);
    }

    public function toString(): string
    {
        return $this->language;
    }
}
