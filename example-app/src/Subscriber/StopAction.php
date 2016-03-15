<?php
/**
 * Copyright © 2016 Elbek Azimov. Contacts: <atom.azimov@gmail.com>
 */

namespace ExampleApp\Subscriber;


use Atom\Uploader\Event\IUploadEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

abstract class StopAction implements EventSubscriberInterface
{
    /**
     * @return string
     */
    public static function getEventName()
    {
    }

    public function stopAction(IUploadEvent $event)
    {
        $event->stopAction();
    }

    public static function getSubscribedEvents()
    {
        return [
            static::getEventName() => 'stopAction'
        ];
    }
}