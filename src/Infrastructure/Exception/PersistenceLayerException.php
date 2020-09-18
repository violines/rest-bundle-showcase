<?php

declare(strict_types=1);

namespace App\Infrastructure\Exception;

class PersistenceLayerException extends \LogicException implements \Throwable
{
    public static function fromMaxInsert(int $limit, int $actual): self
    {
        return new self(sprintf('Maximum of %s exceeded. Attempted: %s', $limit, $actual));
    }
}
