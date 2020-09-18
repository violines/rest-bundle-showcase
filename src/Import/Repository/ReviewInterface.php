<?php

namespace App\Import\Repository;

use App\Import\Model\Review;

interface ReviewInterface
{
    /**
     * @param Review[] $reviews
     */
    public function saveMany(array $reviews): void;
}
