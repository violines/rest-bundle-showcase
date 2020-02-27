<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Candy as CandyModel;
use App\Repository\CandyRepository;
use App\Struct\Import\Candy as CandyStruct;
use App\Struct\Ok;
use Symfony\Component\Routing\Annotation\Route;

class ImportController
{
    private CandyRepository $candyRepository;

    public function __construct(
        CandyRepository $candyRepository
    ) {
        $this->candyRepository = $candyRepository;
    }

    /**
     * @Route("/import", methods={"POST"}, name="import")
     */
    public function import(CandyStruct ...$candies): Ok
    {
        $_candies = [];

        foreach ($candies as $candy) {
            $models = CandyModel::fromImportStruct($candy);
            array_push($_candies, ...$models);
        }

        $this->candyRepository->insert($_candies);

        return Ok::create();
    }
}
