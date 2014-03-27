<?php
namespace Local;

use Exception\Filesystem\RuntimeException;
use Molajo\Filesystem\Connection;

/**
 * Tests Local Filesystem Adapter: Exists Methods
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0
 */
class LocalExistsTest extends Data
{
    /**
     * Adapter
     *
     * @var    object  Molajo\Filesystem\Driver
     * @since  1.0
     */
    protected $adapter;

    /**
     * Setup testing
     *
     * @return  $this
     * @since   1.0
     */
    protected function setUp()
    {
        parent::setUp();

        $this->options = array();

        $this->adapter = 'Local';
        $this->adapter = new Connection();

        return $this;
    }

    /**
     * Successful: File exists
     *
     * @return  $this
     * @since   1.0
     * @covers  Molajo\Filesystem\Driver::exists
     * @covers  Molajo\Filesystem\Adapter\Local::exists
     */
    public function testFileExistsSuccessful()
    {
        $base       = substr(__DIR__, 0, strlen(__DIR__) - 5);
        $this->path = $base . '/.dev/Tests/Data/test1.txt';
        $results    = $this->adapter->exists($this->path);
        $this->assertEquals(true, $results);

        return $this;
    }

    /**
     * Successful: Folder exists
     *
     * @return  $this
     * @since   1.0
     * @covers  Molajo\Filesystem\Driver::exists
     * @covers  Molajo\Filesystem\Adapter\Local::exists
     */
    public function testFolderExistsSuccessful()
    {
        $base       = substr(__DIR__, 0, strlen(__DIR__) - 5);
        $this->path = $base . '/.dev/Tests/Data';
        $results    = $this->adapter->exists($this->path);
        $this->assertEquals(true, $results);

        return $this;
    }

    /**
     * Successful: File does not exist
     *
     * @return  $this
     * @since   1.0
     * @covers  Molajo\Filesystem\Driver::exists
     * @covers  Molajo\Filesystem\Adapter\Local::exists
     */
    public function testFileDoesNotExistSuccessful()
    {
        $base       = substr(__DIR__, 0, strlen(__DIR__) - 5);
        $this->path = $base . '/.dev/Tests/Data/test1ZZZZZZ.txt';
        $results    = $this->adapter->exists($this->path);
        $this->assertEquals(false, $results);

        return $this;
    }

    /**
     * Successful: Folder does not exist
     *
     * @return  $this
     * @since   1.0
     * @covers  Molajo\Filesystem\Driver::exists
     * @covers  Molajo\Filesystem\Adapter\Local::exists
     */
    public function testFolderDoesNotExistSuccessful()
    {
        $base       = substr(__DIR__, 0, strlen(__DIR__) - 5);
        $this->path = $base . '/.dev/Tests/DataZZZZZZ';
        $results    = $this->adapter->exists($this->path);
        $this->assertEquals(false, $results);

        return $this;
    }

    /**
     * Successful: Folder does not exist
     *
     * @return  $this
     * @since   1.0
     * @covers  Molajo\Filesystem\Driver::exists
     * @covers  Molajo\Filesystem\Adapter\Local::exists
     * @expectedException Exception\Filesystem\RuntimeException
     */
    public function testEmptyPathNotSuccessful()
    {
        $this->path = null;
        $results    = $this->adapter->exists($this->path);

        return $this;
    }

    /**
     * Tear Down testing
     *
     * @return  $this
     * @since   1.0
     */
    protected function tearDown()
    {
        parent::tearDown();

        return $this;
    }
}
