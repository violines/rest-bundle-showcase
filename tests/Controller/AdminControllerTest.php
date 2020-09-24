<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\EntityManagerInterface;

class AdminControllerTest extends WebTestCase
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

    public function testUserList()
    {
        $headers = array_replace(self::DEFAULT_HEADERS, [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $this->getToken()
        ]);

        $this->client->request('GET', '/admin/users', [], [], $headers);

        $this->assertResponseIsSuccessful();

        $this->assertEquals(
            json_decode($this->expectedUserList),
            json_decode($this->client->getResponse()->getContent())
        );
    }

    public function testUserEdit()
    {;
        $headers = array_replace(self::DEFAULT_HEADERS, [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $this->getToken()
        ]);

        $this->client->request('POST', '/admin/user/edit', [], [], $headers, $this->userPayload);

        $this->assertResponseIsSuccessful();
    }

    private function getToken(): string
    {
        $this->client->request(
            'POST',
            'login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{"username":"admin@test.test","password":"pass1234"}'
        );

        $responseContent = $this->client->getResponse()->getContent();

        return json_decode($responseContent)->token;
    }

    private const DEFAULT_HEADERS = [
        'HTTP_ACCEPT' => 'application/json',
        'HTTP_ACCEPT_LANGUAGE' => 'de-DE'
    ];

    private $expectedUserList = <<<'EOT'
    [
        {
            "id":1,
            "email": "import@test.test",
            "key": "USKRZAOT",
            "roles": [
                "ROLE_IMPORT"
            ]
        },
        {
            "id":2,
            "email": "admin@test.test",
            "roles": [
                "ROLE_ADMIN"
            ]
        },
        {
            "id":3,
            "email": "user@test.test",
            "roles": []
        }
    ]
    EOT;

    private $userPayload = <<<'EOT'
    {
        "id": 3,
        "email": "user@test.test",
        "roles": []
    }
    EOT;
}
