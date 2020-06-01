<?php

declare(strict_types=1);

namespace App\Controller;

use App\Import\Import;
use App\Import\Model\Candy as CandyModel;
use App\DTO\Import\Candy as ImportCandy;
use App\DTO\Ok;
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
     * @Route("/import/candies", methods={"POST"}, name="import_candies")
     */
    public function candies(ImportCandy ...$candies): Ok
    {
        $_candies = [];

        foreach ($candies as $candy) {
            $models = CandyModel::fromImportDTO($candy);
            array_push($_candies, ...$models);
        }

        /** @var CandyModel[] $_candies  */
        $this->import->candies($_candies);

        return Ok::create();
    }
}
