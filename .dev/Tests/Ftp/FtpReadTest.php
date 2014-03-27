<?php
namespace Ftp;

use Molajo\Filesystem\Driver as adapter;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-01-26 at 06:27:20.
 */
class FtpReadTest extends Data
{
    /**
     * Initialises Adapter
     */
    protected function setUp()
    {
        parent::setUp();

        $this->adapter = 'Ftp';
        $this->action  = 'Read';
        $this->path    = BASE_FOLDER . '/.dev/Tests/Data/test1.txt';
        $this->options = array();
    }

    /**
     * Did this in Connect
     *
     * @covers Molajo\Filesystem\Adapter\Ftp::read
     */
    public function testReadSuccessful()
    {
        $this->path = '/Data/test1.txt';

        $this->options = array(
            'username'          => 'test',
            'password'          => 'test',
            'host'              => 'localhost',
            'port'              => '21',
            'timeout'           => '15',
            'passive_mode'      => true,
            'initial_directory' => 'Data',
            'ftp_mode'          => FTP_ASCII,
            'persistence'       => false
        );

        $adapter = new adapter($this->action, $this->path, $this->adapter, $this->options);

        $this->assertEquals('Ftp', $adapter->fs->adapter);
        $this->assertEquals('/', $adapter->fs->root);
        $this->assertEquals(false, $adapter->fs->persistence);
        $this->assertEquals(0755, $adapter->fs->default_directory_permissions);
        $this->assertEquals(0644, $adapter->fs->default_file_permissions);
        $this->assertEquals(false, $adapter->fs->read_only);
        $this->assertEquals(true, $adapter->fs->is_readable);
        $this->assertEquals(true, $adapter->fs->is_writable);
        $this->assertEquals(false, $adapter->fs->is_executable);

        $this->assertEquals(
            '/Data/test1.txt',
            $adapter->fs->path
        );
        $this->assertEquals(true, $adapter->fs->exists);
        $this->assertEquals(
            '/Data/test1.txt',
            $adapter->fs->absolute_path
        );
        $this->assertEquals(true, $adapter->fs->is_absolute_path);
        $this->assertEquals(false, $adapter->fs->is_directory);
        $this->assertEquals(true, $adapter->fs->is_file);
        $this->assertEquals(false, $adapter->fs->is_link);
        $this->assertEquals('file', $adapter->fs->type);
        $this->assertEquals('test1.txt', $adapter->fs->name);
        $this->assertEquals(
            '/Data',
            $adapter->fs->parent
        );
        $this->assertEquals('txt', $adapter->fs->extension);
        $this->assertEquals('test1', $adapter->fs->file_name_without_extension);
        $this->assertEquals(20, $adapter->fs->size);
        $this->assertEquals('text/plain', $adapter->fs->mime_type);
        $this->assertEquals('test1', $adapter->fs->file_name_without_extension);

        $this->assertEquals('yabba, dabba, doo', trim($adapter->fs->data));

        $this->assertEquals(1, $adapter->fs->owner);
        $this->assertEquals(501, $adapter->fs->group);

        $this->assertEquals(true, $adapter->fs->is_readable);
        $this->assertEquals(true, $adapter->fs->is_writable);
        $this->assertEquals(false, $adapter->fs->is_executable);

        $this->assertEquals('test', $adapter->fs->username);
        $this->assertEquals('test', $adapter->fs->password);
        $this->assertEquals('localhost', $adapter->fs->host);
        $this->assertEquals('ftp', $adapter->fs->connection_type);
        $this->assertEquals(21, $adapter->fs->port);
        $this->assertEquals(15, $adapter->fs->timeout);
        $this->assertEquals(true, $adapter->fs->passive_mode);
        $this->assertEquals('Data', $adapter->fs->initial_directory);
        $this->assertEquals(true, $adapter->fs->is_connected);
        $this->assertEquals('73f661695573b01306ca7eafadca485b', $adapter->fs->hash_file_md5);
        $this->assertEquals('4448b5a7175376584c124937c238c4afeb9b7509', $adapter->fs->hash_file_sha1);

        return;
    }

    /**
     * @covers Molajo\Filesystem\Adapter\Ftp::read
     * @expectedException InvalidArgumentException
     */
    public function testReadUnsuccessful()
    {
        $this->path = '/Data/testreally-is-not-there.txt';
        $adapter    = new adapter($this->action, $this->path, $this->adapter, $this->options = array());

        return;
    }

    /**
     * @covers Molajo\Filesystem\Adapter\Ftp::read
     * @expectedException InvalidArgumentException
     */
    public function testReadNotAFile()
    {
        $this->path = '';
        $adapter    = new adapter($this->action, $this->path, $this->adapter, $this->options = array());

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
