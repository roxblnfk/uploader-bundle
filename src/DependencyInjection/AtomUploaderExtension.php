<?php
/**
 * Copyright © 2016 Elbek Azimov. Contacts: <atom.azimov@gmail.com>
 */

namespace Atom\UploaderBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class AtomUploaderExtension extends Extension
{
    /** {@inheritdoc} */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator([
            __DIR__.'/../Resources/config',
        ]));

        $loader->load('services.xml');
        $loader->load('namers.xml');
        $loader->load('filesystem-adapters.xml');

        $config = $this->processConfiguration(new Configuration(), $configs);

        foreach ($config['drivers'] as $driver) {
            $path = sprintf(__DIR__.'/../Resources/config/drivers/%s.xml', $driver);

            if (!file_exists($path)) {
                continue;
            }

            $loader->load(sprintf('drivers/%s.xml', $driver));
            $container->setParameter(sprintf('atom_uploader.driver_type_%s', $driver), true);
        }

        $container->setParameter('atom_uploader.model_manager_name', $config['model_manager_name']);
    }
}
