<?php

declare(strict_types=1);

namespace App\Controller;

use App\Import\Import;
use App\Import\Model\Candy;
use App\View\Ok as OkView;
use Symfony\Component\Routing\Annotation\Route;

class ImportController
{
    private Import $import;

    public function __construct(Import $import)
    {
        $this->import = $import;
    }

    /**
     * @Route("/import/candies", methods={"POST"}, name="import_candies")
     */
    public function candies(Candy ...$candies): OkView
    {
        $this->import->candies($candies);

        return OkView::create();
    }
}
