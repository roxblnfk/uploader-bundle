<?php
/**
 * Copyright © 2016 Elbek Azimov. Contacts: <atom.azimov@gmail.com>.
 */

namespace Atom\UploaderBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
 */
class RegisterMappingHelpersCompiler implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $taggedServices = $container->findTaggedServiceIds('atom_uploader.mapping.helper_repo');
        $definition = $container->findDefinition('atom_uploader.mapping.helper_repo');

        foreach ($taggedServices as $serviceId => $tags) {
            // all unused drivers will be removed after DI optimization phase
            $container->findDefinition($serviceId)->setPublic(false);
            $definition->addMethodCall('addHelper', [new Reference($serviceId)]);
        }
    }
}
