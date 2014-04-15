<?php
namespace Local;

use Molajo\Filesystem\Adapter\Local;
use Molajo\Filesystem\Driver;

/**
 * Tests Local Filesystem Adapter: Write Methods
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0.0
 */
class LocalWriteTest extends Data
{
    /**
     * Adapter
     *
     * @var    object  Molajo\Filesystem\Adapter\Local
     * @since  1.0
     */
    protected $adapter;

    /**
     * Adapter
     *
     * @var    object  Molajo\Filesystem\Driver
     * @since  1.0
     */
    protected $adapter;

    /**
     * Options
     *
     * @var    array
     * @since  1.0
     */
    protected $options;

    /**
     * Setup testing
     *
     * @return  $this
     * @since   1.0
     */
    protected function setUp()
    {
        parent::setUp();

        $this->adapter = new Local($this->options);

        $this->options = array();
        $this->adapter = new Adapter($this->adapter);

        return $this;
    }

    /**
     * Successful: Write no replace
     *
     * @return  $this
     * @since   1.0
     * @covers  Molajo\Filesystem\Driver::write
     * @covers  Molajo\Filesystem\Adapter\Local::write
     */
    public function testSuccessfulWrite()
    {
        $base = substr(__DIR__, 0, strlen(__DIR__) - 5);

        $this->path = $base . '/.dev/Tests' . '/' . 'test33.txt';

        echo $this->path;
        if (file_exists($this->path)) {
            \unlink($this->path);
        }

        $data     = 'Here are the words to write.';
        $replace  = false;
        $append   = false;
        $truncate = false;

        $this->assertFileNotExists($this->path);

        $this->adapter->write($this->path, $data, $replace, $append, $truncate);

        $this->assertFileExists($this->path);

        return;
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
    }
}
