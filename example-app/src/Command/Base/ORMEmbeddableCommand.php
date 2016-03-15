<?php
/**
 * Copyright © 2016 Elbek Azimov. Contacts: <atom.azimov@gmail.com>
 */

namespace ExampleApp\Command\Base;


use ExampleApp\Entity\EntityHavingEmbeddedFile;

abstract class ORMEmbeddableCommand extends ORMCommand
{
    /**
     * @param        $entityClass
     * @param string $driver
     *
     * @return EntityHavingEmbeddedFile|object
     * @throws \ExampleApp\Exception\ObjectNotFoundException
     */
    protected function getEntity($entityClass = EntityHavingEmbeddedFile::class, $driver = 'orm_embeddable')
    {
        return parent::getEntity($entityClass, $driver);
    }
}