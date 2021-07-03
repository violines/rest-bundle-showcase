<?php

declare(strict_types=1);

namespace App\Domain\Catalog\Query;

use Violines\RestBundle\HttpApi\HttpApi;

#[HttpApi(requestInfoSource: HttpApi::QUERY_STRING)]
final class FilterProducts
{
    public int $ratingFrom = 1;

    public int $ratingTo = 5;

    public string $language = 'en';

    public int $page = 1;

    public function setRatingFrom(string $ratringFrom)
    {
        $this->ratingFrom = (int) $ratringFrom;
    }

    public function setRatingTo(string $ratringTo)
    {
        $this->ratingTo = (int) $ratringTo;
    }

    public function setPage(string $page)
    {
        if (1 < (int)$page) {
            $this->page = (int)$page;
            return;
        }

        $this->page = 1;
    }
}
