<?php

namespace App\Tests\Controller;

use App\Tests\RestTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends RestTestCase
{
    protected function setUp()
    {
        parent::setUp();

        /** @var EntityManagerInterface $em */
        $em = self::$container->get('doctrine.orm.default_entity_manager');
        $em->getConnection()->exec(file_get_contents(__DIR__ . '/../../fixtures/test.sql'));
    }

    public function testUsers()
    {
        $headers = array_replace(self::DEFAULT_HEADERS, [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $this->getAdminToken()
        ]);

        $this->client->request('GET', '/admin/users', [], [], $headers);

        $this->assertResponseIsSuccessful();

        $this->assertEquals(
            json_decode($this->expectedUserList),
            json_decode($this->client->getResponse()->getContent())
        );
    }

    public function testEditUser()
    {;
        $headers = array_replace(self::DEFAULT_HEADERS, [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $this->getAdminToken()
        ]);

        $this->client->request('POST', '/admin/user', [], [], $headers, $this->userPayload);

        $this->assertResponseIsSuccessful();
    }

    public function testRegister()
    {
        $headers = array_replace(self::DEFAULT_HEADERS, [
            'CONTENT_TYPE' => 'application/json',
        ]);

        $this->client->request(
            'POST',
            'de/register',
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
            'de/register',
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
            'HTTP_AUTHORIZATION' => 'Bearer ' . $this->getUserToken()
        ]);

        $this->client->request('GET', 'de/profile/3', [], [], $headers);

        $this->assertResponseIsSuccessful();
        $this->assertJsonResponse($this->expectedProfile, $this->client->getResponse());
    }

    public function testProfileFailed()
    {
        $headers = array_replace(self::DEFAULT_HEADERS, [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer 12345'
        ]);

        $this->client->request('GET', 'de/profile/1', [], [], $headers);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

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
