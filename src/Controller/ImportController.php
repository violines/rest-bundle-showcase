<?php

declare(strict_types=1);

namespace App\Controller;

use App\Import\Import;
use App\Import\Model\Candy as CandyModel;
use App\Struct\Import\Candy as CandyStruct;
use App\Struct\Ok;
use Symfony\Component\Routing\Annotation\Route;

class ImportController
{
    private Import $import;

    public function __construct(
        Import $import
    ) {
        $this->import = $import;
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

        /** @var CandyModel[] $_candies  */
        $this->import->importCandies($_candies);

        return Ok::create();
    }
}
