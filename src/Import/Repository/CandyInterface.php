<?php

namespace App\Import\Repository;

use App\Import\Model\Candy;

interface CandyInterface
{
    /**
     * @param Candy[]
     */
    public function saveMany(array $candies): void;
}
