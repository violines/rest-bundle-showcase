<?php

namespace App\Infrastructure\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use TerryApiBundle\Event\SerializeEvent;

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
