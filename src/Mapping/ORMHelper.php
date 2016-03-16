<?php


namespace Atom\UploaderBundle\Mapping;


use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Events;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ORMHelper extends AbstractMappingHelper
{
    public function getRealClasses(array $mappings, ContainerBuilder $container)
    {

        /** @var ObjectManager[] $managers */
        $managers = $container->get('doctrine')->getManagers();
        $mappedClasses = [];

        foreach ($managers as $manager) {
            /** @var \Doctrine\ORM\Mapping\ClassMetadata[] $entityMetadataMap */
            $entityMetadataMap = $manager->getMetadataFactory()->getAllMetadata();

            foreach ($entityMetadataMap as $entityMetadata) {
                $className = $entityMetadata->getName();

                if ($entityMetadata->isEmbeddedClass || false === $this->findMappingByClassName($mappings, $className)) {
                    continue;
                }

                $mappedClasses[$className] = $className;
            }
        }

        $this->updateSubscriber($container, $mappedClasses, $mappings);

        return $mappedClasses;
    }

    private function updateSubscriber(ContainerBuilder $container, array $fileReferenceEntities, array $mappings)
    {
        if (empty($fileReferenceEntities)) {
            return;
        }

        $events = [
            Events::preUpdate,
            Events::postUpdate,
            Events::prePersist,
            Events::postPersist,
            Events::postFlush
        ];

        if ($this->hasOptionInMappings($mappings, 'delete_on_remove')) {
            $events[] = Events::postRemove;
        }

        if ($this->hasOptionInMappings($mappings, ['inject_uri_on_load', 'inject_file_info_on_load'])) {
            $events[] = Events::postLoad;
        }

        $definition = $container->findDefinition('atom_uploader.orm.listener');
        $definition->replaceArgument(1, $fileReferenceEntities);
        $definition->replaceArgument(2, $events);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'orm';
    }
}