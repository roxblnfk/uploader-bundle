<?php
/**
 * Copyright © 2016 Elbek Azimov. Contacts: <atom.azimov@gmail.com>
 */

namespace ExampleApp\Command\Base;

use Doctrine\ORM\EntityManagerInterface;
use ExampleApp\Entity\UploadableEntity;
use ExampleApp\Exception\ObjectNotFoundException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class ORMCommand extends Command
{
    protected $em;

    public function __construct($name, EventDispatcherInterface $dispatcher, EntityManagerInterface $em)
    {
        parent::__construct($name, $dispatcher);
        $this->em = $em;
    }

    /**
     * @param        $entityClass
     * @param string $driver
     *
     * @return UploadableEntity|object
     *
     * @throws ObjectNotFoundException
     */
    protected function getEntity($entityClass = UploadableEntity::class, $driver = 'orm')
    {
        $id = $this->getId();

        $entity = $this->em->find($entityClass, $id);

        if (null === $entity) {
            throw new ObjectNotFoundException($id, $driver);
        }

        return $entity;
    }

    protected function view($id = null, array $fileReference = null)
    {
        $this->em->flush();

        parent::view($id, $fileReference);
    }
}
