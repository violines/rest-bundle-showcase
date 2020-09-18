<?php

declare(strict_types=1);

namespace App\Controller;

use App\Import\Import;
use App\Import\Model\Product;
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
     * @Route("/import/products", methods={"POST"}, name="import_products")
     */
    public function products(Product ...$products): OkView
    {
        $this->import->products($products);

        return OkView::create();
    }
}
