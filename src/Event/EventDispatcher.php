<?php
/**
 * Copyright © 2016 Elbek Azimov. Contacts: <atom.azimov@gmail.com>.
 */

namespace Atom\UploaderBundle\Event;

use Atom\Uploader\Event\IEventDispatcher;
use Atom\Uploader\Event\IUploadEvent;
use Atom\Uploader\Metadata\FileMetadata;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class EventDispatcher implements IEventDispatcher
{
    private $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param string       $eventName
     * @param object       $fileReference
     * @param FileMetadata $metadata
     *
     * @return IUploadEvent
     */
    public function dispatch($eventName, $fileReference, FileMetadata $metadata)
    {
        return $this->dispatcher->dispatch($eventName, new UploadEvent($fileReference, $metadata));
    }
}
