<?php

declare(strict_types=1);

namespace App\Controller;

use App\Import\Import;
use App\Import\Model\Candy as CandyModel;
use App\Import\HTTPApi\Candy as CandyHTTPApi;
use App\View\Ok as OkView;
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
    public function candies(CandyHTTPApi ...$candies): OkView
    {
        $_candies = [];

        foreach ($candies as $candy) {
            array_push($_candies, ...$candy->toImport());
        }

        /** @var CandyModel[] $_candies  */
        $this->import->candies($_candies);

        return OkView::create();
    }
}
