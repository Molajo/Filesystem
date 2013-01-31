<?php
namespace Tests\Filesystem;

use \PHPUnit_Framework_TestCase;

use Molajo\Filesystem\Adapter;

use Molajo\Filesystem\Exception\FileNotFoundException;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-01-26 at 06:27:20.
 */
class LocalCopyTest extends Data
{
    /**
     * @var Adapter Name
     */
    protected $adapter_name;

    /**
     * @var Action
     */
    protected $action;

    /**
     * @var Path
     */
    protected $path;

    /**
     * @var Options
     */
    protected $options = array();

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var Directories
     */
    protected $directories;

    /**
     * @var  Files
     */
    protected $files;

    /**
     * Initialises Adapter
     */
    protected function setUp()
    {
        parent::setUp();

        /** initialise call */
        $this->adapter_name = 'Local';
        $this->action       = 'Copy';

        return;
    }

    /**
     * Should default target directory to $this->path
     *
     * @covers Molajo\Filesystem\Type\Local::copy
     */
    public function testSuccessfulCopySingleFileBlankDirectory()
    {
        $temp = BASE_FOLDER . '/Tests/Data/Testcases';

        $this->options = array(
            'target_directory'       => '',
            'target_name'            => 'test2.txt',
            'replace'                => false,
            'target_filesystem_type' => 'Local'
        );
        $this->path    = BASE_FOLDER . '/Tests/Data/Testcases/test1.txt';

        $connect = new Adapter($this->adapter_name, $this->path, $this->action, $this->options);

        $this->assertEquals($this->getSize($this->path), $this->getSize($temp . '/test2.txt'));
        $this->assertGreaterThan(0, $this->getSize($temp . '/test2.txt'));

        return;
    }

    /**
     * @covers Molajo\Filesystem\Type\Local::copy
     */
    public function testSuccessfulCopySingleFile()
    {
        $temp = BASE_FOLDER . '/Tests/Data/Testcases';

        $this->options = array(
            'target_directory'       => '',
            'target_name'            => 'test2.txt',
            'replace'                => false,
            'target_filesystem_type' => 'Local'
        );
        $this->path    = BASE_FOLDER . '/Tests/Data/Testcases/test1.txt';

        $connect = new Adapter($this->adapter_name, $this->path, $this->action, $this->options);

        $this->assertEquals($this->getSize($this->path), $this->getSize($temp . '/test2.txt'));
        $this->assertGreaterThan(0, $this->getSize($temp . '/test2.txt'));

        return;
    }

    /**
     * @covers Molajo\Filesystem\Type\Local::copy
     */
    public function testCreateSingleFolder()
    {
        $temp = BASE_FOLDER . '/Tests/Data';

        $this->options = array(
            'target_directory'       => $temp,
            'target_name'            => 'didit',
            'replace'                => false,
            'target_filesystem_type' => 'Local'
        );
        $this->path    = BASE_FOLDER . '/Tests/Data/doit';

        $connect = new Adapter($this->adapter_name, $this->path, $this->action, $this->options);

        $this->assertEquals($this->getSize($this->path), $this->getSize($temp . '/didit'));
        $this->assertGreaterThan(0, $this->getSize($temp . '/didit'));

        return;
    }

    /**
     * @covers Molajo\Filesystem\Type\Local::copy
     */
    public function testCopyMultipleFolder()
    {
        $temp = BASE_FOLDER . '/Tests/Data';

        $this->options = array(
            'target_directory'       => $temp,
            'target_name'            => 'Amy',
            'replace'                => false,
            'target_filesystem_type' => 'Local'
        );
        $this->path    = BASE_FOLDER . '/Tests/Data/Testcases';

        $connect = new Adapter($this->adapter_name, $this->path, $this->action, $this->options);

        $this->assertEquals($this->getSize($this->path), $this->getSize($temp . '/Amy'));
        $this->assertGreaterThan(0, $this->getSize($temp . '/Amy'));

        return;
    }

    /**
     * @covers Molajo\Filesystem\Type\Local::copy
     * @expectedException Molajo\Filesystem\Exception\FileException
     */
    public function testNotAFile()
    {
        $this->options = array(
            'target_directory'       => BASE_FOLDER . '/Tests/Data',
            'target_name'            => 'Amy',
            'replace'                => false,
            'target_filesystem_type' => 'Local'
        );
        $this->path    = BASE_FOLDER . '/Tests/Dataeeeeee';
        $connect       = new Adapter($this->adapter_name, $this->path, $this->action, $this->options);

        return;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        parent::tearDown();
    }
}
