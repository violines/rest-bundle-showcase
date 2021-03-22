<?php

namespace App\Tests\Controller;

use App\Tests\RestTestCase;
use Symfony\Component\HttpFoundation\Response;

class ProductControllerTest extends RestTestCase
{
    public function testProducts()
    {
        $this->client->request('GET', 'de/products', [], [], self::DEFAULT_HEADERS);

        $this->assertResponseIsSuccessful();
        $this->assertJsonResponse($this->expectedProductList, $this->client->getResponse());
    }

    public function testProductsFiltered()
    {
        $this->client->request('GET', 'de/products?ratingFrom=3&ratingTo=5', [], [], self::DEFAULT_HEADERS);

        $this->assertResponseIsSuccessful();
        $this->assertJsonResponse($this->expectedProductsFiltered, $this->client->getResponse());
    }

    public function testProduct()
    {
        $this->client->request('GET', 'de/product/1', [], [], self::DEFAULT_HEADERS);

        $this->assertResponseIsSuccessful();
        $this->assertJsonResponse($this->expectedProductDetail, $this->client->getResponse());
    }

    public function testProductNotFound()
    {
        $this->client->request('GET', 'de/product/999', [], [], self::DEFAULT_HEADERS);

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertJsonResponse($this->expectedNotFound, $this->client->getResponse());
    }

    private $expectedProductList = <<<'EOT'
    [
        {
            "gtin": "886037363214",
            "weight": 5,
            "name": "Weiße Schokolade mit Krisp",
            "average_rating": 0
        },
        {
            "gtin": "9272037363324",
            "weight": 10,
            "name": "Erdnuss Cups",
            "average_rating": 4
        },
        {
            "gtin": "5567037363214",
            "weight": 15,
            "name": "Zartbitter Schokolade",
            "average_rating": 0
        },
        {
            "gtin": "893037363214",
            "weight": 20,
            "name": "Prinzessinen Rolle",
            "average_rating": 0
        }
    ]
    EOT;


    private $expectedProductsFiltered = <<<'EOT'
    [
        {
            "gtin": "9272037363324",
            "weight": 10,
            "name": "Erdnuss Cups",
            "average_rating": 4
        }
    ]
    EOT;

    private $expectedProductDetail = <<<'EOT'
    {
        "gtin": "886037363214",
        "weight": 5,
        "name": "Weiße Schokolade mit Krisp",
        "average_rating": 0
    }
    EOT;

    private $expectedNotFound = <<<'EOT'
    {
        "message": "The requested resource was not found"
    }    
    EOT;
}
