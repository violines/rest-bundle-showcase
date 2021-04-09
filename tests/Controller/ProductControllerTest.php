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

    public function testShouldListCategories()
    {
        $this->client->request('GET', 'de/categories', [], [], self::DEFAULT_HEADERS);

        $this->assertResponseIsSuccessful();
        $this->assertJsonResponse($this->expectedCategories, $this->client->getResponse());
    }

    private $expectedProductList = <<<'JSON'
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
    JSON;


    private $expectedProductsFiltered = <<<'JSON'
    [
        {
            "gtin": "9272037363324",
            "weight": 10,
            "name": "Erdnuss Cups",
            "average_rating": 4
        }
    ]
    JSON;

    private $expectedProductDetail = <<<'JSON'
    {
        "gtin": "886037363214",
        "weight": 5,
        "name": "Weiße Schokolade mit Krisp",
        "average_rating": 0
    }
    JSON;

    private $expectedNotFound = <<<'JSON'
    {
        "message": "The requested resource was not found"
    }    
    JSON;

    private $expectedCategories = <<<'JSON'
    [
        {
            "key": "Chocolate",
            "sorting": 1
        },
        {
            "key": "Biscuits",
            "sorting": 2
        },
        {
            "key": "Bubblegum",
            "sorting": 3
        }
    ]
    JSON;
}
