<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

class FrontendControllerTest extends WebTestCase
{
    private $client;

    public function setUp()
    {
        parent::setUp();

        $this->client = static::createClient();

        /** @var EntityManagerInterface $em */
        $em = self::$container->get('doctrine.orm.default_entity_manager');
        $em->getConnection()->exec(file_get_contents(__DIR__ . '/../../fixtures/candy.sql'));
    }

    public function testCandyList()
    {
        $this->client->request('GET', '/frontend/candy/list', [], [], self::DEFAULT_HEADERS);

        $this->assertResponseIsSuccessful();
        $this->assertEquals(
            json_decode($this->expectedCandyList),
            json_decode($this->client->getResponse()->getContent())
        );
    }

    public function testCandyDetail()
    {
        $this->client->request('GET', '/frontend/candy/886037363214', [], [], self::DEFAULT_HEADERS);

        $this->assertResponseIsSuccessful();
        $this->assertEquals(
            json_decode($this->expectedCandyDetail),
            json_decode($this->client->getResponse()->getContent())
        );
    }

    public function testCandyNotFound()
    {
        $this->client->request('GET', '/frontend/candy/999', [], [], self::DEFAULT_HEADERS);

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertEquals(
            json_decode($this->expectedNotFound),
            json_decode($this->client->getResponse()->getContent())
        );
    }

    public function testReview()
    {
        $headers = array_replace(self::DEFAULT_HEADERS, [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $this->getToken()
        ]);

        $this->client->request(
            'POST',
            '/frontend/review',
            [],
            [],
            $headers,
            $this->reviewPayload
        );

        $this->assertResponseIsSuccessful();
    }

    public function testRegister()
    {
        $headers = array_replace(self::DEFAULT_HEADERS, [
            'CONTENT_TYPE' => 'application/json',
        ]);

        $this->client->request(
            'POST',
            '/frontend/register',
            [],
            [],
            $headers,
            $this->registerPayload
        );

        $this->assertResponseIsSuccessful();
    }

    public function testRegisterFailed()
    {
        $headers = array_replace(self::DEFAULT_HEADERS, [
            'CONTENT_TYPE' => 'application/json',
        ]);

        $this->client->request(
            'POST',
            '/frontend/register',
            [],
            [],
            $headers,
            $this->registerFailedPayload
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testProfile()
    {
        $headers = array_replace(self::DEFAULT_HEADERS, [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $this->getToken()
        ]);

        $this->client->request('GET', '/frontend/profile', [], [], $headers);

        $this->assertResponseIsSuccessful();

        $this->assertEquals(
            json_decode($this->expectedProfile),
            json_decode($this->client->getResponse()->getContent())
        );
    }

    public function testProfileFailed()
    {
        $headers = array_replace(self::DEFAULT_HEADERS, [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer 12345'
        ]);

        $this->client->request('GET', '/frontend/profile', [], [], $headers);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    private function getToken(): string
    {
        $this->client->request(
            'POST',
            '/frontend/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{"username":"user@test.test","password":"pass1234"}'
        );

        $responseContent = $this->client->getResponse()->getContent();

        return json_decode($responseContent)->token;
    }

    private const DEFAULT_HEADERS = [
        'HTTP_ACCEPT' => 'application/json',
        'HTTP_ACCEPT_LANGUAGE' => 'de-DE'
    ];

    private $expectedCandyList = <<<'EOT'
    [
        {
            "gtin": "886037363214",
            "weight": 5,
            "name": "Weiße Schokolade mit Krisp",
            "average_rating": null
        },
        {
            "gtin": "9272037363324",
            "weight": 10,
            "name": "Erdnuss Cups",
            "average_rating": null
        },
        {
            "gtin": "5567037363214",
            "weight": 15,
            "name": "Zartbitter Schokolade",
            "average_rating": null
        },
        {
            "gtin": "893037363214",
            "weight": 20,
            "name": "Prinzessinen Rolle",
            "average_rating": null
        }
    ]
    EOT;

    private $expectedCandyDetail = <<<'EOT'
    {
        "gtin": "886037363214",
        "weight": 5,
        "name": "Weiße Schokolade mit Krisp",
        "average_rating": {
            "taste": 0,
            "ingredients": 0,
            "healthiness": 0,
            "packaging": 0,
            "availability": 0
        }
    }
    EOT;

    private $expectedNotFound = <<<'EOT'
    {
        "message": "The requested resource was not found"
    }    
    EOT;

    private $reviewPayload = <<<'EOT'
    {
        "gtin": "886037363214",
        "taste": 5,
        "ingredients": 4,
        "healthiness": 5,
        "packaging": 2,
        "availability": 1,
        "comment": "This is awesome."
    }
    EOT;

    private $registerPayload = <<<'EOT'
    {
        "email": "register@test.test",
        "password": "12345678"
    }
    EOT;

    private $registerFailedPayload = <<<'EOT'
    {
        "email": "user@test.test",
        "password": "12345678"
    }
    EOT;

    private $expectedProfile = <<<'EOT'
    {
        "email": "user@test.test",
        "roles": [],
        "key": null
    }
    EOT;
}
