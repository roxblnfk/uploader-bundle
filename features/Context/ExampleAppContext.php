<?php

namespace Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Symfony\Component\Filesystem\Filesystem;
use PHPUnit_Framework_TestCase as Test;


/**
 * Defines application features from the specific context.
 */
class ExampleAppContext implements Context, SnippetAcceptingContext
{
    use ContextVars;

    private $fs;

    private $statusCode;

    private $output;

    private $outputData;

    private $subscribers;

    private $driver;

    private $config;

    public function __construct()
    {
        $projectRoot = realpath(__DIR__ . '/../../example-app');
        $this->setVar('project root', $projectRoot);
        $this->setVar('upload path', $projectRoot . '/web/uploads');
        $this->setVar('tmp', $projectRoot . '/var/tmp');
        $this->setVar('log', $projectRoot . '/var/logs');
        $this->setVar('config path', $projectRoot . '/var/tmp/config.yml');

        $this->fs = new Filesystem();
        $this->outputData = [];
        $this->statusCode = 0;
        $this->subscribers = [];
        $this->driver = 'orm';
    }

    /**
     * @Given I have selected driver :driver
     */
    public function iHaveSelectedDriver($driver)
    {
        $this->driver = $driver;
    }

    /**
     * @Then I should get a success status
     */
    public function iShouldGetASuccessStatus()
    {
        Test::assertTrue(0 === $this->statusCode, $this->getLasErrorMessage());
    }

    /**
     * @Given I have a file named :filename
     * @Given I have a file named :filename and with content:
     */
    public function iHaveAFileNamed($filename, PyStringNode $content = null)
    {
        $data = $content ? $content->getRaw() : '';
        $filePath = $this->injectVars($filename);
        $this->fs->dumpFile($filePath, $data);
        Test::assertTrue(file_exists($filePath));

    }


    /**
     * @When I upload the file :filename
     */
    public function iUploadTheFile($filename)
    {
        $input = $this->buildCommand('upload', null, $filename);
        $this->run($input, 'last uploaded %s');
    }

    /**
     * @Given amount of files in upload path is :count
     */
    public function amountOfFilesInUploadPathIs($count)
    {
        $files = $this->scanDirWithoutDotFiles($this->getVar('upload path'));
        Test::assertCount((int)$count, $files);
    }

    /**
     * @Given I have got an uploaded file named :filename
     */
    public function iHaveAnUploadedFileNamed($filename)
    {
        $this->iHaveAFileNamed($filename);
        $this->iUploadTheFile($filename);
        $this->iShouldGetASuccessStatus();
    }

    /**
     * @When I delete the object with id :id
     */
    public function iDeleteObjectWithId($id)
    {
        $input = $this->buildCommand('remove', $id);
        $this->run($input, 'last removed %s');
    }

    /**
     * @When I update object with id :id to replace the file to the new file :newFileName
     */
    public function iUpdateObjectToReplaceTheFileToTheNewFile($id, $newFileName)
    {
        $input = $this->buildCommand('update', $id, $newFileName);
        $this->run($input, 'last updated %s');
    }

    /**
     * @When I get an object with id :id
     */
    public function iGetAnObjectWithId($id)
    {
        $input = $this->buildCommand('get', $id);
        $this->run($input, 'last obtained %s');
    }

    /**
     * @Then I should see uri :uri
     */
    public function iShouldSeeUri($uri)
    {
        $uri = $this->injectVars($uri);
        Test::assertTrue(isset($this->outputData['fileReference']));
        Test::assertEquals($uri, $this->outputData['fileReference']['uri']);
    }

    /**
     * @Then I should see file info :fileInfo
     */
    public function iShouldSeeFileInfo($fileInfo)
    {
        $expected = $this->normalizePath($this->injectVars($fileInfo));
        Test::assertTrue(isset($this->outputData['fileReference']));
        $actual = $this->normalizePath($this->outputData['fileReference']['fileInfo']);
        Test::assertEquals($expected, $actual);
    }

    /**
     * @Given I register a subscriber :subscriberClass
     */
    public function iRegisterASubscriber($subscriberClass)
    {
        $this->subscribers[] = $subscriberClass;
    }

    /**
     * @Given The file :filename is exist
     */
    public function theFileIsExist($filename)
    {
        Test::assertTrue(file_exists($this->injectVars($filename)));
    }

    /**
     * @Given I use following config:
     */
    public function iUseFollowingConfig(PyStringNode $string)
    {
        $this->config = $string->getRaw();
    }

    /**
     * @BeforeScenario
     * @AfterScenario
     */
    public function ACleanDatabase()
    {
        $this->cleanORMDatabase();
    }

    /**
     * @BeforeScenario
     * @AfterScenario
     */
    public function aCleanUploadPath()
    {
        $this->cleanDirectory($this->getVar('upload path'));
    }

    /**
     * @BeforeScenario
     * @AfterScenario
     */
    public function aCleanTmpPath()
    {
        $this->cleanDirectory($this->getVar('tmp'));
    }

    /**
     * @BeforeScenario
     * @AfterScenario
     */
    public function aCleanLogPath()
    {
        try {
            $this->cleanDirectory($this->getVar('log'));
        } catch (\Exception $e) {
            // do nothing
        }
    }

    private function run($command, $varsTemplate)
    {
        if ($this->config) {
            file_put_contents($this->getVar('config path'), $this->config);
            $this->clearCache();
        }

        exec(sprintf('%s/bin/test-console doctrine:schema:update --force --no-debug -e test', $this->getVar('project root')));
        $this->output = system($command, $this->statusCode);
        $this->outputData = [];

        if ($this->config) {
            @unlink($this->getVar('config path'));
            $this->clearCache();
        }

        if (0 !== $this->statusCode) {
            return;
        }

        $outputData = json_decode($this->output, true);

        if (null !== $outputData) {
            $this->outputData = $outputData;
            $this->refreshVars($varsTemplate);
        }
    }

    private function clearCache()
    {
        exec(sprintf('%s/bin/test-console cache:clear --no-warmup --no-debug -e test', $this->getVar('project root')));
    }

    private function buildCommand($action, $id = null, $file = null)
    {
        $id = $this->injectVars($id);
        $file = $this->injectVars($file);

        $command = [
            sprintf('%s/bin/test-console', $this->getVar('project root')),
            sprintf('%s:%s', $this->driver, $action),
            '-e',
            'test',
            '--no-debug'
        ];

        foreach ($this->subscribers as $subscriber) {
            $command[] = '--with-subscriber';
            $command[] = $subscriber;
        }

        if ($id) {
            $command[] = '--id';
            $command[] = $id;
        }

        if ($file) {
            $command[] = $file;
        }

        return implode(' ', $command);
    }

    private function refreshVars($template)
    {
        if (isset($this->outputData['id'])) {
            $this->setVar(sprintf($template, 'object id'), $this->outputData['id']);
        }

        if (!isset($this->outputData['fileReference'])) {
            return;
        }

        $fileReference = $this->outputData['fileReference'];

        if (isset($fileReference['file'])) {
            $this->setVar(sprintf($template, 'filename'), $fileReference['file']);
        }

        if (isset($fileReference['uri'])) {
            $this->setVar(sprintf($template, 'uri'), $fileReference['uri']);
        }

        if (isset($fileReference['fileInfo'])) {
            $this->setVar(sprintf($template, 'file info'), $fileReference['fileInfo']);
        }
    }

    private function getLasErrorMessage()
    {
        return sprintf('Error occurred at last command. Error message: %s%s', PHP_EOL, $this->output);
    }

    private function cleanDirectory($directory)
    {
        $files = $this->scanDirWithoutDotFiles($directory);

        $this->fs->remove($files);
    }

    private function scanDirWithoutDotFiles($directory)
    {
        $files = new \FilesystemIterator($directory, \FilesystemIterator::SKIP_DOTS);

        return array_filter(
            iterator_to_array($files),
            function (\SplFileInfo $val) {
                return 0 !== strpos($val->getFilename(), '.');
            }
        );
    }

    private function cleanORMDatabase()
    {
        @unlink($this->getVar('project root') . '/src/Resources/data/orm.sqlite');
    }

    private function normalizePath($path)
    {
        if (false !== strpos($path, '://')) {
            return str_replace('\\', '/', $path);
        }

        return str_replace('/', DIRECTORY_SEPARATOR, $path);
    }
}
