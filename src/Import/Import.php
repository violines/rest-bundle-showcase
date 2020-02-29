<?php

declare(strict_types=1);

namespace App\Import;

use App\Import\Model\Candy;
use App\Repository\CandyRepository;

class Import
{
    private CandyRepository $candyRepository;

    public function __construct(
        CandyRepository $candyRepository
    ) {
        $this->candyRepository = $candyRepository;
    }

    /**
     * @param Candy[] $candies
     */
    public function importCandies(array $candies): void
    {
        $this->candyRepository->insert($candies);
    }
}
