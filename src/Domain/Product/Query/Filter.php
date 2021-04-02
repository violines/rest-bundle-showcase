<?php

declare(strict_types=1);

namespace App\Domain\Product\Query;

use Violines\RestBundle\HttpApi\HttpApi;

#[HttpApi(requestInfoSource: HttpApi::QUERY_STRING)]
final class Filter
{
    public $ratingFrom = 0;

    public $ratingTo = 5;

    public function setRatingFrom(string $ratringFrom)
    {
        $this->ratingFrom = (int) $ratringFrom;
    }

    public function setRatingTo(string $ratringTo)
    {
        $this->ratingTo = (int) $ratringTo;
    }
}
