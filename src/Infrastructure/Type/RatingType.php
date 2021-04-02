<?php

declare(strict_types=1);

namespace App\Infrastructure\Type;

use App\Domain\Review\Value\Rating;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\IntegerType;

final class RatingType extends IntegerType
{
    const RATING = 'rating';

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return Rating::fromInt($value);
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
