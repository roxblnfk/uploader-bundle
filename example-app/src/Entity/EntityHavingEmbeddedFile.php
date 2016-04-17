<?php
/**
 * Copyright © 2016 Elbek Azimov. Contacts: <atom.azimov@gmail.com>
 */

namespace ExampleApp\Entity;

use Atom\Uploader\Model\Embeddable\FileReference;

class EntityHavingEmbeddedFile
{
    private $id;

    private $fileReference;

    public function __construct(FileReference $fileReference)
    {
        $this->fileReference = $fileReference;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return FileReference
     */
    public function getFileReference()
    {
        return $this->fileReference;
    }
}
