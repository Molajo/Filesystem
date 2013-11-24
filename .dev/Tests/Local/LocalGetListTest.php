<?php
namespace Local;

use Exception\Filesystem\AdapterException;
use Molajo\Filesystem\Connection;

/**
 * Tests Local Filesystem Handler: Getlist Methods
 *
 * @package    Molajo
 * @copyright  2013 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0
 */
class LocalGetlistTest extends Data
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
        $this->adapter = new Connection();
    }

    /**
     * Not Recursive w Files and Folders
     *
     * @return  $this
     * @since   1.0
     * @covers  Molajo\Filesystem\Adapter::getGetlist
     * @covers  Molajo\Filesystem\Handler\Local::getGetlist
     */
    public function testGetlistRecursiveFalseSuccessful()
    {
        $this->path      = BASE_FOLDER . '/.dev/Tests/Hold';
        $recursive       = false;
        $extension_list  = null;
        $include_files   = true;
        $include_folders = true;
        $filename_mask   = null;

        $list = $this->adapter->getList(
            $this->path,
            $recursive,
            $extension_list,
            $include_files,
            $include_folders,
            $filename_mask
        );

        $this->assertEquals(3, count($list));

        return $this;
    }

    /**
     * Recursive w Files and Folders
     *
     * @return  $this
     * @since   1.0
     * @covers  Molajo\Filesystem\Adapter::getGetlist
     * @covers  Molajo\Filesystem\Handler\Local::getGetlist
     */
    public function testGetlistRecursiveTrueSuccessful()
    {
        $this->path      = BASE_FOLDER . '/.dev/Tests/Hold';
        $recursive       = true;
        $extension_list  = null;
        $include_files   = true;
        $include_folders = true;
        $filename_mask   = null;

        $list = $this->adapter->getList(
            $this->path,
            $recursive,
            $extension_list,
            $include_files,
            $include_folders,
            $filename_mask
        );

        $this->assertEquals(43, count($list));

        return $this;
    }

    /**
     * Recursive with Folders but no Files
     *
     * @return  $this
     * @since   1.0
     * @covers  Molajo\Filesystem\Adapter::getGetlist
     * @covers  Molajo\Filesystem\Handler\Local::getGetlist
     */
    public function testGetlistRecursiveNoFilesSuccessful()
    {
        $this->path      = BASE_FOLDER . '/.dev/Tests/Hold';
        $recursive       = true;
        $extension_list  = null;
        $include_files   = false;
        $include_folders = true;
        $filename_mask   = null;

        $list = $this->adapter->getList(
            $this->path,
            $recursive,
            $extension_list,
            $include_files,
            $include_folders,
            $filename_mask
        );

        $this->assertEquals(12, count($list));

        return $this;
    }

    /**
     * Recursive with Files but no Folders
     *
     * @return  $this
     * @since   1.0
     * @covers  Molajo\Filesystem\Adapter::getGetlist
     * @covers  Molajo\Filesystem\Handler\Local::getGetlist
     */
    public function testGetlistRecursiveNoFoldersSuccessful()
    {
        $this->path      = BASE_FOLDER . '/.dev/Tests/Hold';
        $recursive       = true;
        $extension_list  = null;
        $include_files   = true;
        $include_folders = false;
        $filename_mask   = null;

        $list = $this->adapter->getList(
            $this->path,
            $recursive,
            $extension_list,
            $include_files,
            $include_folders,
            $filename_mask
        );

        $this->assertEquals(31, count($list));

        return $this;
    }

    /**
     * Recursive with Files but no Folders
     *
     * @return  $this
     * @since   1.0
     * @covers  Molajo\Filesystem\Adapter::getGetlist
     * @covers  Molajo\Filesystem\Handler\Local::getGetlist
     */
    public function testGetlistRecursiveNoFoldersOnlyTxtSuccessful()
    {
        $this->path      = BASE_FOLDER . '/.dev/Tests/Hold';
        $recursive       = true;
        $extension_list  = 'txt';
        $include_files   = true;
        $include_folders = false;
        $filename_mask   = null;

        $list = $this->adapter->getList(
            $this->path,
            $recursive,
            $extension_list,
            $include_files,
            $include_folders,
            $filename_mask
        );

        $this->assertEquals(31, count($list));

        return $this;
    }

    /**
     * Recursive with Files but no Folders
     *
     * @return  $this
     * @since   1.0
     * @covers  Molajo\Filesystem\Adapter::getGetlist
     * @covers  Molajo\Filesystem\Handler\Local::getGetlist
     */
    public function testGetlistRecursiveNoFoldersOnlyPdfSuccessful()
    {
        $this->path      = BASE_FOLDER . '/.dev/Tests/Hold';
        $recursive       = true;
        $extension_list  = 'pdf,doc';
        $include_files   = true;
        $include_folders = false;
        $filename_mask   = null;

        $list = $this->adapter->getList(
            $this->path,
            $recursive,
            $extension_list,
            $include_files,
            $include_folders,
            $filename_mask
        );

        $this->assertEquals(0, count($list));

        return $this;
    }

    /**
     * Recursive with Files but no Folders
     *
     * @return  $this
     * @since   1.0
     * @covers  Molajo\Filesystem\Adapter::getGetlist
     * @covers  Molajo\Filesystem\Handler\Local::getGetlist
     */
    public function testGetlistRecursiveNoFoldersTest2Successful()
    {
        $this->path      = BASE_FOLDER . '/.dev/Tests/Hold';
        $recursive       = true;
        $extension_list  = '';
        $include_files   = true;
        $include_folders = false;
        $filename_mask   = 'st2';

        $list = $this->adapter->getList(
            $this->path,
            $recursive,
            $extension_list,
            $include_files,
            $include_folders,
            $filename_mask
        );

        $this->assertEquals(10, count($list));

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
