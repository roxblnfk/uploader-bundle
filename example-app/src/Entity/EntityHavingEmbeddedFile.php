<?php


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