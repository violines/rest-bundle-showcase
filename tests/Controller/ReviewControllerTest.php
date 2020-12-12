<?php

namespace App\Tests\Controller;

use App\Tests\RestTestCase;
use Doctrine\ORM\EntityManagerInterface;

class ReviewControllerTest extends RestTestCase
{
    protected function setUp()
    {
        parent::setUp();

        /** @var EntityManagerInterface $em */
        $em = self::$container->get('doctrine.orm.default_entity_manager');
        $em->getConnection()->exec(file_get_contents(__DIR__ . '/../../fixtures/test.sql'));
    }

    public function testCreateReview()
    {
        $headers = array_replace(self::DEFAULT_HEADERS, [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $this->getUserToken()
        ]);

        $this->client->request(
            'POST',
            'de/review',
            [],
            [],
            $headers,
            $this->reviewPayload
        );

        $this->assertResponseIsSuccessful();
    }

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
}
