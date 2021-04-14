<?php

declare(strict_types=1);

namespace App\Domain\Product\Query;

use Violines\RestBundle\HttpApi\HttpApi;

#[HttpApi(requestInfoSource: HttpApi::QUERY_STRING)]
final class FilterProducts
{
    public int $ratingFrom = 1;

    public int $ratingTo = 5;

    public string $language = 'en';

    public function setRatingFrom(string $ratringFrom)
    {
        $this->ratingFrom = (int) $ratringFrom;
    }

    public function setRatingTo(string $ratringTo)
    {
        $this->ratingTo = (int) $ratringTo;
    }
}
