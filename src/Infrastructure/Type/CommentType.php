<?php

declare(strict_types=1);

namespace App\Infrastructure\Type;

use App\Domain\Review\Value\Comment;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

final class CommentType extends StringType
{
    const COMMENT = 'comment';

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return Comment::fromString($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value->toString();
    }

    public function getName()
    {
        return self::COMMENT;
    }
}
