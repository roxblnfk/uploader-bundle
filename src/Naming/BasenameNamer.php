<?php
/**
 * Copyright © 2016 Elbek Azimov. Contacts: <atom.azimov@gmail.com>
 */

namespace Atom\UploaderBundle\Naming;


use Atom\Uploader\Naming\EscapeFilename;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class BasenameNamer extends \Atom\Uploader\Naming\BasenameNamer
{
    use EscapeFilename;

    /**
     * @param \SplFileInfo $file
     *
     * @return string
     */
    public function name(\SplFileInfo $file)
    {
        if ($file instanceof UploadedFile) {
            return $this->escape($file->getClientOriginalName());
        }

        return parent::name($file);
    }
}