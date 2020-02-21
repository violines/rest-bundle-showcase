<?php

declare(strict_types=1);

namespace App\ValueObject;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class Client
{
    private const DEFAULT_LANGUAGE = 'en-GB';
    private const ACCEPT_LANGUAGE = 'Accept-Language';

    /**
     * @Assert\Type("string")
     */
    public $acceptLanguage;

    private function __construct(string $acceptLanguage)
    {
        $this->acceptLanguage = $acceptLanguage;
    }

    public static function fromRequest(Request $request): self
    {
        $headers = $request->headers;

        $acceptLanguage = $headers->get(self::ACCEPT_LANGUAGE, self::DEFAULT_LANGUAGE);

        return new self($acceptLanguage);
    }

    public function getContentLanguage(): string
    {
        return mb_substr($this->acceptLanguage, 0, 2);
    }
}
