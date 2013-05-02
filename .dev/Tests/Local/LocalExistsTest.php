<?php
namespace Local;

use Molajo\Filesystem\Exception\AdapterException;
use Molajo\Filesystem\Connection;

/**
 * Tests Local Filesystem Handler: Exists Methods
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @since     1.0
 */
class LocalExistsTest extends Data
{
    /**
     * Adapter
     *
     * @var    object  Molajo\Filesystem\Adapter
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

        $this->handler = 'Local';
        $this->adapter = new Connection();

        return $this;
    }

    /**
     * Successful: File exists
     *
     * @return  $this
     * @since   1.0
     * @covers  Molajo\Filesystem\Adapter::exists
     * @covers  Molajo\Filesystem\Handler\Local::exists
     */
    public function testFileExistsSuccessful()
    {
        $this->path = BASE_FOLDER . '/.dev/Tests/Data/test1.txt';
        $results    = $this->adapter->exists($this->path);
        $this->assertEquals(true, $results);

        return $this;
    }

    /**
     * Successful: Folder exists
     *
     * @return  $this
     * @since   1.0
     * @covers  Molajo\Filesystem\Adapter::exists
     * @covers  Molajo\Filesystem\Handler\Local::exists
     */
    public function testFolderExistsSuccessful()
    {
        $this->path = BASE_FOLDER . '/.dev/Tests/Data';
        $results    = $this->adapter->exists($this->path);
        $this->assertEquals(true, $results);

        return $this;
    }

    /**
     * Successful: File does not exist
     *
     * @return  $this
     * @since   1.0
     * @covers  Molajo\Filesystem\Adapter::exists
     * @covers  Molajo\Filesystem\Handler\Local::exists
     */
    public function testFileDoesNotExistSuccessful()
    {
        $this->path = BASE_FOLDER . '/.dev/Tests/Data/test1ZZZZZZ.txt';
        $results    = $this->adapter->exists($this->path);
        $this->assertEquals(false, $results);

        return $this;
    }

    /**
     * Successful: Folder does not exist
     *
     * @return  $this
     * @since   1.0
     * @covers  Molajo\Filesystem\Adapter::exists
     * @covers  Molajo\Filesystem\Handler\Local::exists
     */
    public function testFolderDoesNotExistSuccessful()
    {
        $this->path = BASE_FOLDER . '/.dev/Tests/DataZZZZZZ';
        $results    = $this->adapter->exists($this->path);
        $this->assertEquals(false, $results);

        return $this;
    }


    /**
     * Successful: Folder does not exist
     *
     * @return  $this
     * @since   1.0
     * @covers  Molajo\Filesystem\Adapter::exists
     * @covers  Molajo\Filesystem\Handler\Local::exists
     * @expectedException Molajo\Filesystem\Exception\AdapterException
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
