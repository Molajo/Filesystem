<?php
namespace Local;

use Exception\Filesystem\RuntimeException;
use Molajo\Filesystem\Connection;

/**
 * Tests Local Filesystem Adapter: Read Methods
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0
 */
class LocalReadTest extends Data
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
        $base = substr(__DIR__, 0, strlen(__DIR__) - 5);

        parent::setUp();

        $this->adapter = 'Local';
        $this->path    = $base . '/.dev/Tests/Data/test1.txt';
        $this->adapter = new Connection();

        return $this;
    }

    /**
     * Successful Read for Existing File
     *
     * @return  $this
     * @since   1.0
     * @covers  Molajo\Filesystem\Driver::read
     * @covers  Molajo\Filesystem\Adapter\Local::read
     */
    public function testReadSuccessful()
    {
        $base    = substr(__DIR__, 0, strlen(__DIR__) - 5);
        $results = $this->adapter->read($this->path);
        $this->assertEquals('yabba, dabba, doo', trim($results));

        return $this;
    }

    /**
     * Unsuccessful Read: File does not exist
     *
     * @return  $this
     * @since   1.0
     * @covers  Molajo\Filesystem\Driver::read
     * @covers  Molajo\Filesystem\Adapter\Local::read
     * @expectedException Exception\Filesystem\RuntimeException
     */
    public function testReadUnsuccessful()
    {
        $base       = substr(__DIR__, 0, strlen(__DIR__) - 5);
        $this->path = $base . '/.dev/Tests/Data/testreally-is-not-there.txt';
        $results    = $this->adapter->read($this->path);

        return $this;
    }

    /**
     * Unsuccessful Read: $path is for a folder, not a file
     *
     * @return  $this
     * @since   1.0
     * @covers  Molajo\Filesystem\Driver::read
     * @covers  Molajo\Filesystem\Adapter\Local::read
     * @expectedException Exception\Filesystem\RuntimeException
     */
    public function testReadNotAFile()
    {
        $base       = substr(__DIR__, 0, strlen(__DIR__) - 5);
        $this->path = $base . '/.dev/Tests';
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
