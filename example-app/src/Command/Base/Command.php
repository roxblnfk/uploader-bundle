<?php
/**
 * Copyright © 2016 Elbek Azimov. Contacts: <atom.azimov@gmail.com>
 */

namespace ExampleApp\Command\Base;


use ExampleApp\Exception\FileNotFoundException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class Command extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    protected $dispatcher;

    public function __construct($name, EventDispatcherInterface $dispatcher)
    {
        parent::__construct($name);
        $this->dispatcher = $dispatcher;
    }

    abstract protected function doConfigure();

    abstract protected function doExecute();

    final protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
        $this->registerSubscribers();
        $this->doExecute();
    }

    final protected function configure()
    {
        $this->addArgument('with-subscriber', InputArgument::IS_ARRAY, null, []);
        $this->doConfigure();
    }

    private function registerSubscribers()
    {
        if (!$this->input->hasArgument('with-subscriber')) {
            return;
        }

        $subscribers = (array)$this->input->getArgument('with-subscriber');

        foreach ($subscribers as $subscriberClass) {
            $subscriber = new $subscriberClass();
            $this->dispatcher->addSubscriber($subscriber);
        }
    }

    protected function addFileArgument()
    {
        $this->addOption('file', 'f', InputOption::VALUE_REQUIRED, 'File path to upload.');

        return $this;
    }

    protected function addIdOption()
    {
        $this->addOption('id', null, InputOption::VALUE_REQUIRED, 'The id of object which has file.');

        return $this;
    }

    protected function getId()
    {
        return (int)$this->input->getOption('id');
    }

    protected function getFile()
    {
        $file = $this->input->getOption('file');

        if (!file_exists($file)) {
            throw new FileNotFoundException($file);
        }

        return new \SplFileInfo($file);
    }

    /**
     * @param int|null $id
     * @param array $fileReference
     */
    protected function view($id = null, array $fileReference = null)
    {
        $this->output->write(json_encode(compact('id', 'fileReference')));
    }
}