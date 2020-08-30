<?php

declare(strict_types=1);

namespace App\Controller;

use App\Import\Import;
use App\Import\Model\Candy as CandyModel;
use App\Import\HTTPApi\Candy as CandyApi;
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
    public function candies(CandyApi ...$candies): Ok
    {
        $_candies = [];

        foreach ($candies as $candy) {
            array_push($_candies, ...$candy->toImport());
        }

        /** @var CandyModel[] $_candies  */
        $this->import->candies($_candies);

        return Ok::create();
    }
}
