<?php

declare(strict_types=1);

namespace App\Domain\Product\Query;

use App\Domain\Product\Value\Language;

final class FindCategories
{
    public Language $language;

    public function __construct(Language $language)
    {
        $this->language = $language;
    }
}
