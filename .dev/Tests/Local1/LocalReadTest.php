<?php
namespace Local;

use Molajo\Filesystem\Exception\AdapterException;
use Molajo\Filesystem\Connection;

/**
 * Tests Local Filesystem Handler: Read Methods
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @since     1.0
 */
class LocalReadTest extends Data
{
    /**
     * Adapter
     *
     * @var    object  Molajo/Filesystem/Adapter
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

        $this->adapter_handler = 'Local';
        $this->action                     = 'Read';
        $this->path                       = BASE_FOLDER . '/.dev/Tests/Data/test1.txt';
        $this->options                    = array();

        $this->adapter = new Connection();

        return $this;
    }

    /**
     * Successful Read for Existing File
     *
     * @return  $this
     * @since   1.0
     * @covers Molajo\Filesystem\Adapter::read
     * @covers Molajo\Filesystem\Handler\Local::read
     */
    public function testReadSuccessful()
    {
        $results = $this->adapter->read($this->path);
        $this->assertEquals('yabba, dabba, doo', trim($results));

        return $this;
    }

    /**
     * Unsuccessful Read: File does not exist
     *
     * @return  $this
     * @since   1.0
     * @covers Molajo\Filesystem\Adapter::read
     * @covers Molajo\Filesystem\Handler\Local::read
     * @expectedException Molajo\Filesystem\Exception\AdapterException
     */
    public function testReadUnsuccessful()
    {
        $this->path = BASE_FOLDER . '/.dev/Tests/Data/testreally-is-not-there.txt';
        $results    = $this->adapter->read($this->path);

        return $this;
    }

    /**
     * Unsuccessful Read: $path is for a folder, not a file
     *
     * @return  $this
     * @since   1.0
     * @covers Molajo\Filesystem\Adapter::read
     * @covers Molajo\Filesystem\Handler\Local::read
     * @expectedException Molajo\Filesystem\Exception\AdapterException
     */
    public function testReadNotAFile()
    {
        $this->path = BASE_FOLDER . '/.dev/Tests';
        $results    = $this->adapter->read($this->path);

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
