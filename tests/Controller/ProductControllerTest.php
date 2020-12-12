<?php

namespace App\Tests\Controller;

use App\Tests\RestTestCase;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

class ProductControllerTest extends RestTestCase
{
    protected function setUp()
    {
        parent::setUp();

        /** @var EntityManagerInterface $em */
        $em = self::$container->get('doctrine.orm.default_entity_manager');
        $em->getConnection()->exec(file_get_contents(__DIR__ . '/../../fixtures/test.sql'));
    }

    public function testProducts()
    {
        $this->client->request('GET', 'de/products', [], [], self::DEFAULT_HEADERS);

        $this->assertResponseIsSuccessful();
        $this->assertJsonResponse($this->expectedProductList, $this->client->getResponse());
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
            "average_rating": 0
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
