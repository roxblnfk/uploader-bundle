<?php
/**
 * Copyright © 2016 Elbek Azimov. Contacts: <atom.azimov@gmail.com>.
 */

namespace Atom\UploaderBundle\LazyLoad;

use Atom\Uploader\Filesystem\FilesystemAdapterRepo;
use Atom\Uploader\Handler\UploadHandler;
use Atom\Uploader\LazyLoad\IFilesystemAdapterRepoLazyLoader;
use Atom\Uploader\LazyLoad\IUploadHandlerLazyLoader;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LazyLoader implements IUploadHandlerLazyLoader, IFilesystemAdapterRepoLazyLoader
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return FilesystemAdapterRepo
     */
    public function getFilesystemAdapterRepo()
    {
        return $this->container->get('atom_uploader.filesystem_adapter_repo');
    }

    /**
     * @return UploadHandler
     */
    public function getUploadHandler()
    {
        return $this->container->get('atom_uploader.upload_handler');
    }
}
