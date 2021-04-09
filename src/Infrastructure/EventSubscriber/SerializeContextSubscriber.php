<?php

declare(strict_types=1);

namespace App\Infrastructure\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Violines\RestBundle\Serialize\SerializeEvent;

class SerializeContextSubscriber implements EventSubscriberInterface
{
    public function onSerialize(SerializeEvent $event)
    {
        $event->mergeToContext([AbstractObjectNormalizer::SKIP_NULL_VALUES => true]);
    }

    public static function getSubscribedEvents()
    {
        return [
            SerializeEvent::NAME => 'onSerialize',
        ];
    }
}
