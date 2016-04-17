<?php
/**
 * Copyright © 2016 Elbek Azimov. Contacts: <atom.azimov@gmail.com>
 */

namespace ExampleApp\Command\Base;


use Atom\Uploader\Handler\Uploader;
use Doctrine\DBAL\Connection;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class DBALCommand extends Command
{
    /**
     * @var Connection
     */
    protected $conn;

    /**
     * @var Uploader
     */
    protected $uploader;

    public function __construct($name, EventDispatcherInterface $dispatcher, Uploader $uploader, Connection $conn)
    {
        parent::__construct($name, $dispatcher);

        $createScheme = 'CREATE TABLE IF NOT EXISTS uploadable (
          id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
          file VARCHAR(255)
        )';

        $conn->exec($createScheme);
        $this->conn = $conn;
        $this->uploader = $uploader;
    }

    protected function view($id = null, array $fileReference = null)
    {
        if (isset($fileReference['fileInfo']) && $fileReference['fileInfo'] instanceof \SplFileInfo) {
            $fileReference['fileInfo'] = $fileReference['fileInfo']->getPathname();
        }

        parent::view($id, $fileReference);
    }

    protected function getUploadable()
    {
        $statement = $this->conn->prepare('SELECT id, file FROM uploadable t WHERE t.id = :id');
        $statement->bindValue('id', $this->getId());
        $statement->execute();

        return $statement->fetch();
    }
}