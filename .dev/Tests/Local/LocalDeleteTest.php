<?php
namespace Local;

use Molajo\Filesystem\Adapter as adapter;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-01-26 at 06:27:20.
 */
class LocalDeleteTest extends Data
{
    /**
     * Initialises Adapter
     */
    protected function setUp()
    {
        parent::setUp();

        /** initialise call */
        $this->handler = 'Local';
        $this->action  = 'Delete';

        $this->options = array(
            'delete_empty' => false
        );

        return;
    }

    /**
     * @covers Molajo\Filesystem\Handler\Local::delete
     */
    public function testSuccessfulDeleteSingleFile()
    {
        $base          = substr(__DIR__, 0, strlen(__DIR__) - 5);
        $this->options = array(
            'delete_empty' => false
        );
        $this->path    = $base . '/.dev/Tests/Data/Testcases/test1.txt';

        $this->assertfileExists($this->path);

        $adapter = new adapter($this->action, $this->path, $this->handler, $this->options);

        $this->assertfileNotExists($this->path);

        return;
    }

    /**
     * @covers Molajo\Filesystem\Handler\Local::delete
     */
    public function testDeleteSingleFolder()
    {
        $this->options = array(
            'delete_empty' => true
        );
        $this->path    = $base . '/.dev/Tests/Data/doit';

        $this->assertfileExists($this->path);

        $adapter = new adapter($this->action, $this->path, $this->handler, $this->options);

        $this->assertfileNotExists($this->path);

        return;
    }

    /**
     * @covers Molajo\Filesystem\Handler\Local::delete
     */
    public function testDeleteMultipleFolderOnlyFiles()
    {
        $base = substr(__DIR__, 0, strlen(__DIR__) - 5);

        $this->options = array(
            'delete_empty' => false
        );

        $this->path = $base . '/.dev/Tests/Data/Testcases/Directorytree1';

        $this->assertfileExists($this->path);

        $adapter = new adapter($this->action, $this->path, $this->handler, $this->options);

        $this->assertfileNotExists($this->path);

        return;
    }

    /**
     * @covers Molajo\Filesystem\Handler\Local::delete
     */
    public function testDeleteMultipleFolderDeleteAll()
    {
        $base = substr(__DIR__, 0, strlen(__DIR__) - 5);

        $this->options = array(
            'delete_empty' => true
        );

        $this->path = $base . '/.dev/Tests/Data';

        $this->assertfileExists($this->path);

        $adapter = new adapter($this->action, $this->path, $this->handler, $this->options);

        $this->assertfileNotExists($this->path);

        return;
    }

    /**
     * @covers Molajo\Filesystem\Handler\Local::delete
     * @expectedException Exception\Filesystem\RuntimeException
     */
    public function testNotAFile()
    {
        $base = substr(__DIR__, 0, strlen(__DIR__) - 5);

        $this->options = array(
            'delete_empty' => true
        );

        $this->path = $base . '/.dev/Tests/Dataeeeeee';

        $adapter = new adapter($this->action, $this->path, $this->handler, $this->options);

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
