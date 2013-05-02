<?php
namespace Local;

use Molajo\Filesystem\Handler\Local;
use Molajo\Filesystem\Adapter;

/**
 * Tests Local Filesystem Handler: Write Methods
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @since     1.0
 */
class LocalWriteTest extends Data
{
    /**
     * Adapter
     *
     * @var    object  Molajo\Filesystem\Handler\Local
     * @since  1.0
     */
    protected $handler;

    /**
     * Adapter
     *
     * @var    object  Molajo\Filesystem\Adapter
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

        $this->handler = new Local($this->options);

        $this->options = array();
        $this->adapter = new Adapter($this->handler);

        return $this;
    }

    /**
     * Successful: Write no replace
     *
     * @return  $this
     * @since   1.0
     * @covers  Molajo\Filesystem\Adapter::write
     * @covers  Molajo\Filesystem\Handler\Local::write
     */
    public function testSuccessfulWrite()
    {
        $this->path = BASE_FOLDER . '/.dev/Tests' . '/' . 'test33.txt';
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
