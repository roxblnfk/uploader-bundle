<?php
/**
 * Copyright © 2016 Elbek Azimov. Contacts: <atom.azimov@gmail.com>.
 */

namespace Atom\UploaderBundle\Mapping;

use Symfony\Component\DependencyInjection\ContainerBuilder;

class DefaultMappingHelper extends AbstractMappingHelper
{
    /**
     * @param array            $mappings
     * @param ContainerBuilder $container
     *
     * @return array of real classnames.
     */
    public function getRealClasses(array $mappings, ContainerBuilder $container)
    {
        return array_keys($mappings);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'default';
    }
}
