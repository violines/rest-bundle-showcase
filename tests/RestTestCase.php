<?php

namespace App\Tests;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class RestTestCase extends WebTestCase
{
    protected const DEFAULT_HEADERS = [
        'HTTP_ACCEPT' => 'application/json',
        'HTTP_ACCEPT_LANGUAGE' => 'de-DE'
    ];

    protected $client;

    protected Connection $connection;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->connection = self::$kernel->getContainer()->get('doctrine.orm.entity_manager')->getConnection();
    }

    protected function assertJsonResponse(string $expected, Response $response, ?string $message = ''): void
    {
        $this->assertEquals(json_decode($expected), json_decode($response->getContent()), $message);
    }

    protected function getAdminToken(): string
    {
        return $this->getToken('{"username":"admin@test.test","password":"pass1234"}');
    }

    protected function getUserToken(): string
    {
        return $this->getToken('{"username":"user@test.test","password":"pass1234"}');
    }

    private function getToken(string $payload): string
    {
        $this->client->request(
            'POST',
            'login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            $payload
        );

        $responseContent = $this->client->getResponse()->getContent();

        return json_decode($responseContent)->token;
    }
}
