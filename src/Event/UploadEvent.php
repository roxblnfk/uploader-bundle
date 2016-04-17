<?php
/**
 * Copyright © 2016 Elbek Azimov. Contacts: <atom.azimov@gmail.com>
 */

namespace Atom\UploaderBundle\Event;

use Atom\Uploader\Event\IUploadEvent;
use Symfony\Component\EventDispatcher\Event;

class UploadEvent extends Event implements IUploadEvent
{
    use \Atom\Uploader\Event\UploadEvent;
}
