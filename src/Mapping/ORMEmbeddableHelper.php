<?php
/**
 * Copyright © 2016 Elbek Azimov. Contacts: <atom.azimov@gmail.com>
 */

namespace Atom\UploaderBundle\Mapping;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Events;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class ORMEmbeddableHelper extends AbstractMappingHelper
{
    /**
     * @param array $mappings
     * @param ContainerBuilder $container
     *
     * @return array of real classnames.
     */
    public function getRealClasses(array $mappings, ContainerBuilder $container)
    {
        /** @var ObjectManager[] $managers */
        $managers = $container->get('doctrine')->getManagers();
        $mappedClasses = [];
        $fileReferenceProperties = [];

        foreach ($managers as $manager) {
            /** @var \Doctrine\ORM\Mapping\ClassMetadata[] $entityMetadataMap */
            $entityMetadataMap = $manager->getMetadataFactory()->getAllMetadata();

            foreach ($entityMetadataMap as $entityMetadata) {
                $properties = [];

                foreach ($entityMetadata->embeddedClasses as $propertyName => $embedded) {
                    $embeddedClass = $embedded['class'];
                    $metadata = $this->findMappingByClassName($mappings, $embeddedClass);

                    if (false === $metadata) {
                        continue;
                    }

                    $mappedClasses[$embeddedClass] = $embeddedClass;
                    $properties[] = $propertyName;
                }

                if (!empty($properties)) {
                    $fileReferenceProperties[$entityMetadata->getName()] = $properties;
                }
            }
        }

        $this->updateSubscriber($container, $fileReferenceProperties, $mappings);

        return $mappedClasses;
    }

    private function updateSubscriber(ContainerBuilder $container, array $fileReferenceProperties, array $mappings)
    {
        if (empty($fileReferenceProperties)) {
            return;
        }

        $events = [
            Events::preUpdate,
            Events::postUpdate,
            Events::prePersist,
            Events::postPersist,
            Events::postFlush,
        ];

        if ($this->hasOptionInMappings($mappings, 'delete_on_remove')) {
            $events[] = Events::postRemove;
        }

        if ($this->hasOptionInMappings($mappings, ['inject_uri_on_load', 'inject_file_info_on_load'])) {
            $events[] = Events::postLoad;
        }

        $definition = $container->findDefinition('atom_uploader.orm_embeddable.listener');
        $definition->replaceArgument(1, $fileReferenceProperties);
        $definition->replaceArgument(2, $events);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'orm_embeddable';
    }
}
