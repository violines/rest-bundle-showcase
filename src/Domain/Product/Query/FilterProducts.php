<?php

declare(strict_types=1);

namespace App\Domain\Product\Query;

use App\Domain\Product\Value\Language;
use Violines\RestBundle\HttpApi\HttpApi;

#[HttpApi(requestInfoSource: HttpApi::QUERY_STRING)]
final class FilterProducts
{
    public int $ratingFrom = 1;

    public int $ratingTo = 5;

    public Language $language;

    public function setRatingFrom(string $ratringFrom)
    {
        $this->ratingFrom = (int) $ratringFrom;
    }

    public function setRatingTo(string $ratringTo)
    {
        $this->ratingTo = (int) $ratringTo;
    }
}
