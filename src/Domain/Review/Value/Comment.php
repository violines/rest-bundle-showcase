<?php

declare(strict_types=1);

namespace App\Domain\Review\Value;

use Webmozart\Assert\Assert;

final class Comment
{
    private string $comment;

    private function __construct(string $comment)
    {
        Assert::stringNotEmpty($comment);

        $this->comment = $comment;
    }

    public static function fromString(string $comment)
    {
        return new self($comment);
    }

    public function toString(): string
    {
        return $this->comment;
    }
}
