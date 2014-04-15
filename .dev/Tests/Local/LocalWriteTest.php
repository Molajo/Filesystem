<?php
namespace Local;

use Exception\Filesystem\RuntimeException;
use Molajo\Filesystem\Connection;

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

        $this->adapter = 'Local';
        $this->options = array();
        $this->adapter = new Connection();

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

        $this->path = $base . '/.dev/Tests' . '/' . 'test2.txt';

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
     * Successful: Write with replace
     *
     * @return  $this
     * @since   1.0
     * @covers  Molajo\Filesystem\Driver::write
     * @covers  Molajo\Filesystem\Adapter\Local::write
     */
    public function testSuccessfulRewrite()
    {
        $base = substr(__DIR__, 0, strlen(__DIR__) - 5);

        $this->path = $base . '/.dev/Tests' . '/' . 'test2.txt';

        if (file_exists($this->path)) {
            \unlink($this->path);
        }

        $data     = 'Here are the words to write.';
        $replace  = true;
        $append   = false;
        $truncate = false;

        $this->assertFileNotExists($this->path);

        $this->adapter->write($this->path, $data, $replace, $append, $truncate);

        $this->assertFileExists($this->path);

        return;
    }

    /**
     * @covers Molajo\Filesystem\Adapter\Local::write
     * @expectedException Exception\Filesystem\RuntimeException
     */
    public function testUnsuccessfulRewrite()
    {
        $base = substr(__DIR__, 0, strlen(__DIR__) - 5);

        $temp = 'test2.txt';

        $this->options = array(
            'file'    => $temp,
            'replace' => false,
            'data'    => 'Here are the words to write.',
        );

        $this->path = $base . '/.dev/Tests';

        $adapter = new adapter($this->action, $this->path, $this->adapter, $this->options);

        return;
    }

    /**
     * @covers Molajo\Filesystem\Adapter\Local::write
     */
    public function testWriteSingleFolder()
    {
        $base = substr(__DIR__, 0, strlen(__DIR__) - 5);

        $temp = 'OneMoreFolder';

        $this->options = array(
            'file'    => $temp,
            'replace' => false,
            'data'    => ''
        );

        $this->path = $base . '/.dev/Tests/Data';

        $this->assertFileNotExists($this->path . '/' . $temp);

        $adapter = new adapter($this->action, $this->path, $this->adapter, $this->options);

        $this->assertFileExists($this->path . '/' . $temp);

        return;
    }

    /**
     * rmdir($filePath);
     *  unlink($filePath);
     *
     * @covers Molajo\Filesystem\Adapter\Local::write
     */
    public function testWriteMultipleFolders()
    {
        $base = substr(__DIR__, 0, strlen(__DIR__) - 5);

        $temp = 'sometimes.txt';

        $this->options = array(
            'file'    => $temp,
            'replace' => false,
            'data'    => 'Poop'
        );

        $this->path = $base . '/.dev/Tests/Data/OneMoreFolder/Cats/love/Dogs';

        $this->assertFileNotExists($this->path . '/' . $temp);

        $adapter = new adapter($this->action, $this->path, $this->adapter, $this->options);

        $this->assertFileExists($this->path . '/' . $temp);

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
