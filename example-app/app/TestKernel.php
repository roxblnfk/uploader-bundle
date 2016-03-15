<?php


/** @noinspection PhpUndefinedClassInspection */
class TestKernel extends \AppKernel
{
    public function registerContainerConfiguration(\Symfony\Component\Config\Loader\LoaderInterface $loader)
    {
        /** @noinspection PhpUndefinedClassInspection */
        parent::registerContainerConfiguration($loader);

        $resourcePath = __DIR__ . '/../var/tmp/config.yml';

        if (file_exists($resourcePath)) {
            $loader->load($resourcePath);
        }
    }
}