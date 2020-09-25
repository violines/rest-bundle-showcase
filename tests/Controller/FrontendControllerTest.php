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
        $em->getConnection()->exec(file_get_contents(__DIR__ . '/../../fixtures/test.sql'));
    }

    public function testProducts()
    {
        $this->client->request('GET', 'de/frontend/products', [], [], self::DEFAULT_HEADERS);

        $this->assertResponseIsSuccessful();
        $this->assertEquals(
            json_decode($this->expectedProductList),
            json_decode($this->client->getResponse()->getContent())
        );
    }

    public function testProductDetail()
    {
        $this->client->request('GET', 'de/frontend/product/1', [], [], self::DEFAULT_HEADERS);

        $this->assertResponseIsSuccessful();
        $this->assertEquals(
            json_decode($this->expectedProductDetail),
            json_decode($this->client->getResponse()->getContent())
        );
    }

    public function testProductNotFound()
    {
        $this->client->request('GET', 'de/frontend/product/999', [], [], self::DEFAULT_HEADERS);

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertEquals(
            json_decode($this->expectedNotFound),
            json_decode($this->client->getResponse()->getContent())
        );
    }

    public function testCreateReview()
    {
        $headers = array_replace(self::DEFAULT_HEADERS, [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $this->getToken()
        ]);

        $this->client->request(
            'POST',
            'de/frontend/review',
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
            'de/frontend/register',
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
            'de/frontend/register',
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

        $this->client->request('GET', 'de/frontend/profile/3', [], [], $headers);

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

        $this->client->request('GET', 'de/frontend/profile/1', [], [], $headers);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    private function getToken(): string
    {
        $this->client->request(
            'POST',
            'login',
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

    private $reviewPayload = <<<'EOT'
    {
        "productId": 1,
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
        "user_id":3,
        "email": "user@test.test"
    }
    EOT;
}
