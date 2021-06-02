<?php

namespace App\Tests\Controller;

use App\Tests\RestTestCase;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;

class ImportControllerTest extends RestTestCase
{
    public function testImport(): void
    {
        $this->client->request(
            'POST',
            '/import/products',
            [],
            [],
            self::HEADERS,
            $this->importPayload
        );

        $this->assertResponseIsSuccessful();

        $created = $this->connection->fetchAll($this->importSql);

        $this->assertEquals(10, count($created));
    }

    private const HEADERS = [
        'HTTP_ACCEPT' => 'application/json',
        'HTTP_ACCEPT_LANGUAGE' => 'de_DE',
        'CONTENT_TYPE' => 'application/json',
        'HTTP_X-AUTH-TOKEN' => 'USKRZAOT'
    ];

    private $importSql = <<<'EOT'
    SELECT * FROM product 
        WHERE gtin IN (
            '9372610283610', 
            '7682610283411', 
            '9372610283612',
            '7682610283413', 
            '9372610283614', 
            '7682610283415', 
            '9372610283616', 
            '7682610283417', 
            '9372610283618', 
            '7682610283419',
            '9372610283620'
        )
    EOT;

    private $importPayload = <<<'EOT'
    [
        {
            "gtin": "9372610283610",
            "weight": 10,
            "translations": [
                {
                    "language": "de",
                    "title": "Weiße Schokolade"
                },
                {
                    "language": "en",
                    "title": "White Chocolate"
                }
            ]
        },
        {
            "gtin": "7682610283411",
            "weight": 11,
            "translations": [
                {
                    "language": "de",
                    "title": "Weiße Schokolade"
                },
                {
                    "language": "en",
                    "title": "White Chocolate"
                }
            ]
        },
        {
            "gtin": "9372610283612",
            "weight": 12,
            "translations": [
                {
                    "language": "de",
                    "title": "Weiße Schokolade"
                },
                {
                    "language": "en",
                    "title": "White Chocolate"
                }
            ]
        },
        {
            "gtin": "7682610283413",
            "weight": 13,
            "translations": [
                {
                    "language": "de",
                    "title": "Weiße Schokolade"
                },
                {
                    "language": "en",
                    "title": "White Chocolate"
                }
            ]
        },
        {
            "gtin": "9372610283614",
            "weight": 14,
            "translations": [
                {
                    "language": "de",
                    "title": "Weiße Schokolade"
                },
                {
                    "language": "en",
                    "title": "White Chocolate"
                }
            ]
        },
        {
            "gtin": "7682610283415",
            "weight": 15,
            "translations": [
                {
                    "language": "de",
                    "title": "Weiße Schokolade"
                },
                {
                    "language": "en",
                    "title": "White Chocolate"
                }
            ]
        },
        {
            "gtin": "9372610283616",
            "weight": 16,
            "translations": [
                {
                    "language": "de",
                    "title": "Weiße Schokolade"
                },
                {
                    "language": "en",
                    "title": "White Chocolate"
                }
            ]
        },
        {
            "gtin": "7682610283417",
            "weight": 17,
            "translations": [
                {
                    "language": "de",
                    "title": "Weiße Schokolade"
                },
                {
                    "language": "en",
                    "title": "White Chocolate"
                }
            ]
        },
        {
            "gtin": "9372610283618",
            "weight": 18,
            "translations": [
                {
                    "language": "de",
                    "title": "Weiße Schokolade"
                },
                {
                    "language": "en",
                    "title": "White Chocolate"
                }
            ]
        },
        {
            "gtin": "7682610283419",
            "weight": 19,
            "translations": [
                {
                    "language": "de",
                    "title": "Weiße Schokolade"
                },
                {
                    "language": "en",
                    "title": "White Chocolate"
                }
            ]
        }
    ]
    EOT;
}
