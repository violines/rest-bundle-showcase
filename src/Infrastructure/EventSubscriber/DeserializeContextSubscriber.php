<?php

namespace App\Infrastructure\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use TerryApiBundle\Event\DeserializeEvent;

class DeserializeContextSubscriber implements EventSubscriberInterface
{
    public function onDeserialize($event)
    {
        $event->mergeToContext([AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => false]);
    }

    public static function getSubscribedEvents()
    {
        return [
            DeserializeEvent::NAME => 'onDeserialize',
        ];
    }
}
