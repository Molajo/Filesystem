<?php
namespace Local;

use Molajo\Filesystem\Exception\AdapterException;
use Molajo\Filesystem\Connection;

/**
 * Tests Local Filesystem Handler: Metadata Methods
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @since     1.0
 */
class LocalMetadataTest extends Data
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
        $this->path            = BASE_FOLDER . '/.dev/Tests/Data/test1.txt';
        $this->options         = array();
        $this->adapter = new Connection();
    }

    /**
     * Successful GetMetadata for Existing File
     *
     * @return  $this
     * @since   1.0
     * @covers Molajo\Filesystem\Adapter::getMetadata
     * @covers Molajo\Filesystem\Handler\Local::getMetadata
     */
    public function testMetadataSuccessful()
    {
        $metadata = $this->adapter->getMetadata($this->path);

        $this->assertEquals(BASE_FOLDER . '/.dev/Tests/Data/test1.txt', $metadata->path);
        $this->assertEquals(null, $metadata->initial_directory);  //amy
        $this->assertEquals('/', $metadata->root);
        $this->assertEquals(null, $metadata->host);
        $this->assertEquals(493, $metadata->default_directory_permissions);
        $this->assertEquals(420, $metadata->default_file_permissions);
        $this->assertEquals(false, $metadata->read_only);
        $this->assertEquals(true, $metadata->exists);
        $this->assertEquals(BASE_FOLDER . '/.dev/Tests/Data/test1.txt', $metadata->absolute_path);
        $this->assertEquals(true, $metadata->is_absolute_path);
        $this->assertEquals(false, $metadata->is_root);
        $this->assertEquals(false, $metadata->is_directory);
        $this->assertEquals(true, $metadata->is_file);
        $this->assertEquals(false, $metadata->is_link);
        $this->assertEquals('file', $metadata->type);
        $this->assertEquals('test1.txt', $metadata->name);
        $this->assertEquals(BASE_FOLDER . '/.dev/Tests/Data', $metadata->parent);
        $this->assertEquals('txt', $metadata->extension);
        $this->assertEquals('test1', $metadata->name_without_extension);
        $this->assertEquals('text/plain; charset=us-ascii', $metadata->mimetype);
        $this->assertEquals(501, $metadata->owner);
        $this->assertEquals(20, $metadata->group);
        $this->assertEquals(true, $metadata->is_readable);
        $this->assertEquals(true, $metadata->is_writeable);
        $this->assertEquals(false, $metadata->is_executable);
        $this->assertEquals("73f661695573b01306ca7eafadca485b", $metadata->hash_file_md5);
        $this->assertEquals("4448b5a7175376584c124937c238c4afeb9b7509", $metadata->hash_file_sha1);
        $this->assertEquals(18, $metadata->size);

        return $this;
    }

    /**
     * GetMetadata: $path is for a folder
     *
     * @return  $this
     * @since   1.0
     * @covers Molajo\Filesystem\Adapter::getMetadata
     * @covers Molajo\Filesystem\Handler\Local::getMetadata
     */
    public function testGetMetadataForFolder()
    {
        $this->path = BASE_FOLDER . '/.dev/Tests/Hold';
        $metadata = $this->adapter->getMetadata($this->path);

        $this->assertEquals(BASE_FOLDER . '/.dev/Tests/Hold', $metadata->path);
        $this->assertEquals(null, $metadata->initial_directory);  //amy
        $this->assertEquals('/', $metadata->root);
        $this->assertEquals(null, $metadata->host);
        $this->assertEquals(493, $metadata->default_directory_permissions);
        $this->assertEquals(420, $metadata->default_file_permissions);
        $this->assertEquals(false, $metadata->read_only);
        $this->assertEquals(true, $metadata->exists);
        $this->assertEquals(BASE_FOLDER . '/.dev/Tests/Hold', $metadata->absolute_path);
        $this->assertEquals(true, $metadata->is_absolute_path);
        $this->assertEquals(false, $metadata->is_root);
        $this->assertEquals(true, $metadata->is_directory);
        $this->assertEquals(false, $metadata->is_file);
        $this->assertEquals(false, $metadata->is_link);
        $this->assertEquals('directory', $metadata->type);
        $this->assertEquals('Hold', $metadata->name);
        $this->assertEquals(BASE_FOLDER . '/.dev/Tests', $metadata->parent);
        $this->assertEquals(null, $metadata->extension);
        $this->assertEquals(null, $metadata->name_without_extension);
        $this->assertEquals(null, $metadata->mimetype);
        $this->assertEquals(501, $metadata->owner);
        $this->assertEquals(20, $metadata->group);
        $this->assertEquals(true, $metadata->is_readable);
        $this->assertEquals(true, $metadata->is_writeable);
        $this->assertEquals(true, $metadata->is_executable);
        $this->assertEquals("d41d8cd98f00b204e9800998ecf8427e", $metadata->hash_file_md5);
        $this->assertEquals("da39a3ee5e6b4b0d3255bfef95601890afd80709", $metadata->hash_file_sha1);
        $this->assertEquals(730, $metadata->size);

        return $this;
    }
    /**
     * Unsuccessful Read: $path is for a folder, not a file
     *
     * @return  $this
     * @since   1.0
     * @covers Molajo\Filesystem\Adapter::getMetadata
     * @covers Molajo\Filesystem\Handler\Local::getMetadata
     * @expectedException Molajo\Filesystem\Exception\AdapterException
     */
    public function testReadNotAFile()
    {
        $this->path = BASE_FOLDER . '/.dev/Tests/this/file/does/not/exist.txt';
        $results    = $this->adapter->getMetadata($this->path);

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
