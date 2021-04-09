<?php

declare(strict_types=1);

namespace App\Infrastructure\QueryBus;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class QueryBus
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * @param object|Envelope $query
     *
     * @return mixed
     */
    public function query($query)
    {
        return $this->handle($query);
    }
}
