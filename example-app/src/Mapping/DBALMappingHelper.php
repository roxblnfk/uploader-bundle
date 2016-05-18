<?php
/**
 * Copyright © 2016 Elbek Azimov. Contacts: <atom.azimov@gmail.com>
 */

namespace ExampleApp\Mapping;


use Atom\UploaderBundle\Mapping\AbstractMappingHelper;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DBALMappingHelper extends AbstractMappingHelper
{

    /**
     * @param array $mappings
     * @param ContainerBuilder $container
     *
     * @return array of mapping names.
     */
    public function getAvailableMappingsNames(array $mappings, ContainerBuilder $container)
    {
        return ['dbal_uploadable'];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dbal';
    }
}
