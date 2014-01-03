<?php
namespace Local;

use Exception\Filesystem\RuntimeException;
use Molajo\Filesystem\Connection;

/**
 * Tests Local Filesystem Handler: Write Methods
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0
 */
class LocalWriteTest extends Data
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

        $this->handler = 'Local';
        $this->options = array();
        $this->adapter = new Connection();

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
        $this->path = BASE_FOLDER . '/.dev/Tests' . '/' . 'test2.txt';

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
     * @covers  Molajo\Filesystem\Adapter::write
     * @covers  Molajo\Filesystem\Handler\Local::write
     */
    public function testSuccessfulRewrite()
    {
        $this->path = BASE_FOLDER . '/.dev/Tests' . '/' . 'test2.txt';

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
     * @covers Molajo\Filesystem\Handler\Local::write
     * @expectedException Exception\Filesystem\RuntimeException
     */
    public function testUnsuccessfulRewrite()
    {
        $temp = 'test2.txt';

        $this->options = array(
            'file'    => $temp,
            'replace' => false,
            'data'    => 'Here are the words to write.',
        );

        $this->path = BASE_FOLDER . '/.dev/Tests';

        $adapter = new adapter($this->action, $this->path, $this->handler, $this->options);

        return;
    }

    /**
     * @covers Molajo\Filesystem\Handler\Local::write
     */
    public function testWriteSingleFolder()
    {
        $temp = 'OneMoreFolder';

        $this->options = array(
            'file'    => $temp,
            'replace' => false,
            'data'    => ''
        );

        $this->path = BASE_FOLDER . '/.dev/Tests/Data';

        $this->assertFileNotExists($this->path . '/' . $temp);

        $adapter = new adapter($this->action, $this->path, $this->handler, $this->options);

        $this->assertFileExists($this->path . '/' . $temp);

        return;
    }

    /**
     * rmdir($filePath);
     *  unlink($filePath);
     *
     * @covers Molajo\Filesystem\Handler\Local::write
     */
    public function testWriteMultipleFolders()
    {
        $temp = 'sometimes.txt';

        $this->options = array(
            'file'    => $temp,
            'replace' => false,
            'data'    => 'Poop'
        );

        $this->path = BASE_FOLDER . '/.dev/Tests/Data/OneMoreFolder/Cats/love/Dogs';

        $this->assertFileNotExists($this->path . '/' . $temp);

        $adapter = new adapter($this->action, $this->path, $this->handler, $this->options);

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
