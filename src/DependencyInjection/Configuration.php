<?php
/**
 * Copyright © 2016 Elbek Azimov. Contacts: <atom.azimov@gmail.com>.
 */

namespace Atom\UploaderBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root('atom_uploader');

        // @formatter:off
        /* @noinspection PhpUndefinedMethodInspection */
        $node
            ->ignoreExtraKeys()
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('model_manager_name')
                    ->defaultNull()
                    ->info('change it to the name of your entity/document manager if you don\'t want to use the default one.')
                ->end()
            ->end()
            ->fixXmlConfig('driver')
            ->children()
                ->arrayNode('drivers')
                    ->defaultValue(['orm_embeddable'])
                    ->requiresAtLeastOneElement()
                    ->beforeNormalization()
                        ->ifString()
                        ->then(function ($val) { return [$val];})
                    ->end()
                    ->prototype('scalar')->end()
                ->end()
            ->end();

        $this->addMappingsSection($node);

        return $treeBuilder;
    }

    private function addMappingsSection(ArrayNodeDefinition $node)
    {
        $defaults = self::getMappingDefaults();
        // @formatter:off
        /* @noinspection PhpUndefinedMethodInspection */
        $node
            ->fixXmlConfig('mapping')
            ->children()
                ->arrayNode('mappings')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->beforeNormalization()
                            ->ifString()
                            ->then(function ($value) {return ['storage' => $value]; })
                        ->end()
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('file_getter')->defaultValue($defaults['file_getter'])->end()
                            ->scalarNode('file_setter')->defaultValue($defaults['file_setter'])->end()
                            ->scalarNode('uri_setter')->defaultValue($defaults['uri_setter'])->end()
                            ->scalarNode('file_info_setter')->defaultValue($defaults['file_info_setter'])->end()
                            ->scalarNode('fs_prefix')->defaultValue($defaults['fs_prefix'])->end()
                            ->scalarNode('uri_prefix')->defaultValue($defaults['uri_prefix'])->end()
                            ->scalarNode('fs_adapter')->defaultValue($defaults['fs_adapter'])->end()
                            ->scalarNode('naming_strategy')->defaultValue($defaults['naming_strategy'])->end()
                            ->scalarNode('delete_old_file')->defaultValue($defaults['delete_old_file'])->end()
                            ->scalarNode('delete_on_remove')->defaultValue($defaults['delete_on_remove'])->end()
                            ->scalarNode('inject_uri_on_load')->defaultValue($defaults['inject_uri_on_load'])->end()
                            ->scalarNode('inject_file_info_on_load')->defaultValue($defaults['inject_file_info_on_load'])->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $node;
    }

    public static function getMappingDefaults()
    {
        return [
            'file_getter' => 'file',
            'file_setter' => 'file',
            'uri_setter' => 'uri',
            'file_info_setter' => 'fileInfo',
            'fs_prefix' => '%kernel.root_dir%/../web/uploads',
            'uri_prefix' => '/uploads/%s',
            'fs_adapter' => 'local',
            'naming_strategy' => 'unique_id',
            'delete_old_file' => true,
            'delete_on_remove' => true,
            'inject_uri_on_load' => true,
            'inject_file_info_on_load' => false,
        ];
    }
}
