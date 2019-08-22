<?php
/**
 * Copyright © 2016 Elbek Azimov. Contacts: <atom.azimov@gmail.com>
 */

namespace Atom\UploaderBundle\Naming;


use Atom\Uploader\Naming\EscapeFilename;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UniqueNamer extends \Atom\Uploader\Naming\UniqueNamer
{
    use EscapeFilename;

    public function name(\SplFileInfo $file)
    {
        $name = parent::name($file);

        if ($file instanceof UploadedFile && !empty($file->getExtension())) {
            $name .= '.' . $this->escape($file->getClientOriginalExtension());
        }

        return $name;
    }
}
