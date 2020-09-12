<?php

declare(strict_types=1);

namespace App\Type;

use App\ValueObject\Rating;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\Types;

final class RatingType extends Type
{
    const RATING = 'rating';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return Types::INTEGER;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return Rating::new($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value->toInt();
    }

    public function getName()
    {
        return self::RATING;
    }
}
