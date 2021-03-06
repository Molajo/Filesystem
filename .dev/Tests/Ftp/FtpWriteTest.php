<?php
namespace Ftp;

use Exception;
use Exception\Filesystem\RuntimeException;

use Molajo\Filesystem\Driver as adapter;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-01-26 at 06:27:20.
 */
class FtpWriteTest extends Data
{
    /**
     * Initialises Adapter
     */
    protected function setUp()
    {
        parent::setUp();

        $this->adapter = 'Ftp';
        $this->action  = 'Write';
    }

    /**
     * @covers Molajo\Filesystem\Adapter\Ftp::write
     */
    public function testSuccessfulWrite()
    {
        if (file_exists('' . '/' . 'test2.txt')) {
            \unlink('' . '/' . 'test2.txt');
        }

        $temp = 'test2.txt';

        $this->options = array(
            'username'          => 'test',
            'password'          => 'test',
            'host'              => 'localhost',
            'port'              => '21',
            'timeout'           => '15',
            'ftp_mode'          => FTP_ASCII,
            'passive_mode'      => true,
            'initial_directory' => 'Data',
            'persistence'       => false,
            'file'              => $temp,
            'replace'           => false,
            'data'              => 'Here are the words to write.',
        );

        $this->path = '/';

        $this->assertfileNotExists($this->path . '/' . $temp);

        $adapter = new adapter($this->action, $this->path, $this->adapter, $this->options);

        $this->assertfileExists($this->path . '/' . $temp);

        return;
    }

    /**
     * @covers Molajo\Filesystem\Adapter\Ftp::write
     */
    public function testSuccessfulRewrite()
    {
        $temp = 'test2.txt';

        $this->options = array(
            'username'          => 'test',
            'password'          => 'test',
            'host'              => 'localhost',
            'port'              => '21',
            'timeout'           => '15',
            'ftp_mode'          => FTP_ASCII,
            'passive_mode'      => true,
            'initial_directory' => 'Data',
            'persistence'       => false,
            'file'              => $temp,
            'replace'           => true,
            'data'              => 'Here are the words to write.',
        );

        $this->path = '/';

        if (file_exists($this->path . '/' . $temp)) {
        } else {
            \file_put_contents($this->path . '/' . $temp, 'data');
        }

        $this->assertfileExists($this->path . '/' . $temp);

        $adapter = new adapter($this->action, $this->path, $this->adapter, $this->options);

        $this->assertfileExists($this->path . '/' . $temp);

        return;
    }

    /**
     * @covers Molajo\Filesystem\Adapter\Ftp::write
     * @expectedException Exception\Filesystem\RuntimeException
     */
    public function testUnsuccessfulRewrite()
    {
        $temp = 'test2.txt';

        $this->options = array(
            'username'          => 'test',
            'password'          => 'test',
            'host'              => 'localhost',
            'port'              => '21',
            'timeout'           => '15',
            'ftp_mode'          => FTP_ASCII,
            'passive_mode'      => true,
            'initial_directory' => 'Data',
            'persistence'       => false,
            'file'              => $temp,
            'replace'           => false,
            'data'              => 'Here are the words to write.',
        );

        $this->path = '/';

        $adapter = new adapter($this->action, $this->path, $this->adapter, $this->options);

        return;
    }

    /**
     * @covers Molajo\Filesystem\Adapter\Ftp::write
     */
    public function testWriteSingleFolder()
    {
        $temp = 'OneMoreFolder';

        $this->options = array(
            'username'          => 'test',
            'password'          => 'test',
            'host'              => 'localhost',
            'port'              => '21',
            'timeout'           => '15',
            'ftp_mode'          => FTP_ASCII,
            'passive_mode'      => true,
            'initial_directory' => 'Data',
            'persistence'       => false,
            'file'              => $temp,
            'replace'           => false,
            'data'              => ''
        );

        $this->path = '/Data';

        $this->assertfileNotExists($this->path . '/' . $temp);

        $adapter = new adapter($this->action, $this->path, $this->adapter, $this->options);

        $this->assertfileExists($this->path . '/' . $temp);

        return;
    }

    /**
     * rmdir($filePath);
     *  unlink($filePath);
     *
     * @covers Molajo\Filesystem\Adapter\Ftp::write
     */
    public function testWriteMultipleFolders()
    {
        $temp = 'sometimes.txt';

        $this->options = array(
            'username'          => 'test',
            'password'          => 'test',
            'host'              => 'localhost',
            'port'              => '21',
            'timeout'           => '15',
            'ftp_mode'          => FTP_ASCII,
            'passive_mode'      => true,
            'initial_directory' => 'Data',
            'persistence'       => false,
            'file'              => $temp,
            'replace'           => false,
            'data'              => 'Poop'
        );

        $this->path = '/Data/OneMoreFolder/Cats/love/Dogs';

        $this->assertfileNotExists($this->path . '/' . $temp);

        $adapter = new adapter($this->action, $this->path, $this->adapter, $this->options);

        $this->assertfileExists($this->path . '/' . $temp);

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
