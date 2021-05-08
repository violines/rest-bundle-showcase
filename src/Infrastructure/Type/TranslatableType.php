<?php

declare(strict_types=1);

namespace App\Infrastructure\Type;

use Doctrine\DBAL\Types\JsonType;

final class TranslatableType extends JsonType
{
    const TRANSLATABLE = 'translatable';

    public function getName()
    {
        return self::TRANSLATABLE;
    }
}
