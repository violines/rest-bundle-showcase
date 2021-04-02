<?php

declare(strict_types=1);

namespace App\Domain\Product\Exception;

class ProductNotExists extends \LogicException implements \Throwable
{
    public static function id(int $id): self
    {
        return new self(sprintf('Product with id: %s does not exist.', $id));
    }
}
