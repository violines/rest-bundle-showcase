<?php

namespace App\Import\Repository;

use App\Import\Model\Review;

interface ReviewInterface
{
    /**
     * @param Review[] $candies
     */
    public function saveMany(array $reviews): void;
}
