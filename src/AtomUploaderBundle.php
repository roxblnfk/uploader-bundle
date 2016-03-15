<?php

namespace Atom\UploaderBundle;


use Atom\UploaderBundle\DependencyInjection\Compiler\RegisterMappingHelpersCompiler;
use Atom\UploaderBundle\DependencyInjection\Compiler\RegisterMappingsCompiler;
use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AtomUploaderBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new RegisterMappingHelpersCompiler(), PassConfig::TYPE_BEFORE_OPTIMIZATION);
        $container->addCompilerPass(new RegisterMappingsCompiler(), PassConfig::TYPE_OPTIMIZE);

        $this->registerORMEmbeddableMappings($container);
    }

    private function registerORMEmbeddableMappings(ContainerBuilder $container)
    {
        $modelDir = realpath(__DIR__ . '/Resources/config/orm-embeddable-mappings');
        $mappings = [$modelDir => 'Atom\Uploader\Model\Embeddable'];
        $doctrineMappingsPass = DoctrineOrmMappingsPass::createXmlMappingDriver($mappings, ['atom_uploader.model_manager_name'], 'atom_uploader.driver_type_orm_embeddable');
        $container->addCompilerPass($doctrineMappingsPass);
    }
}