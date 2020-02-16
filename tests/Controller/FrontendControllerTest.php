<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class FrontendControllerTest extends WebTestCase
{
    public function testCandyList()
    {
        $client = static::createClient();
        $client->request('GET', '/candy/list', [], [], ['HTTP_ACCEPT' => 'application/json']);

        $this->assertResponseIsSuccessful();
        $this->assertEquals(
            json_decode($this->expectedCandyList),
            json_decode($client->getResponse()->getContent())
        );
    }

    public function testCandyDetail()
    {
        $client = static::createClient();
        $client->request('GET', '/candy/1', [], [], ['HTTP_ACCEPT' => 'application/json']);

        $this->assertResponseIsSuccessful();
        $this->assertEquals(
            json_decode($this->expectedCandyDetail),
            json_decode($client->getResponse()->getContent())
        );
    }

    public function testCandyNotFound()
    {
        $client = static::createClient();
        $client->request('GET', '/candy/999', [], [], ['HTTP_ACCEPT' => 'application/json']);

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertEquals(
            json_decode($this->expectedNotFound),
            json_decode($client->getResponse()->getContent())
        );
    }

    private $expectedCandyList = <<<'EOT'
    [
        {
            "weight": 5,
            "name": "Weiße Schokolade mit Krisp",
            "ratings": null
        },
        {
            "weight": 10,
            "name": "Erdnuss Cups",
            "ratings": null
        },
        {
            "weight": 15,
            "name": "Zartbitter Schokolade",
            "ratings": null
        },
        {
            "weight": 20,
            "name": "Prinzessinen Rolle",
            "ratings": null
        }
    ]
    EOT;

    private $expectedCandyDetail = <<<'EOT'
    {
        "weight": 5,
        "name": "Weiße Schokolade mit Krisp",
        "ratings": null
    }    
    EOT;

    private $expectedNotFound = <<<'EOT'
    {
        "message": "The requested resource was not found"
    }    
    EOT;
}
