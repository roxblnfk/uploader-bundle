<?php
/**
 * Copyright © 2016 Elbek Azimov. Contacts: <atom.azimov@gmail.com>.
 */

namespace Atom\UploaderBundle\Mapping;

class MappingHelperRepo
{
    private $helpers;

    public function __construct()
    {
        $this->helpers = [];
    }

    public function addHelper(IMappingHelper $helper)
    {
        $this->helpers[$helper->getName()] = $helper;
    }

    /**
     * @param string $name
     *
     * @return IMappingHelper|null
     */
    public function getHelper($name)
    {
        return isset($this->helpers[$name]) ? $this->helpers[$name] : null;
    }
}
