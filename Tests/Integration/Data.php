<?php
namespace Tests\Integration;

use DirectoryIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use PHPUnit_Framework_TestCase;


/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-01-26 at 06:27:20.
 */
class Data extends PHPUnit_Framework_TestCase
{
    /**
     * @var Adapter Name
     */
    protected $adapter_name;

    /**
     * @var Action
     */
    protected $action;

    /**
     * @var Path
     */
    protected $path;

    /**
     * @var Options
     */
    protected $options = array();

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var Directories
     */
    protected $directories;

    /**
     * @var Files
     */
    protected $files;

    /**
     * Initialises Adapter
     */
    protected function setUp()
    {
        /** Remove Existing */
        $this->path = BASE_FOLDER . '/Tests/Data';
        if (file_exists($this->path)) {
            $this->delete($this->path);
        }

        $from   = BASE_FOLDER . '/Tests/Hold';
        $to     = BASE_FOLDER . '/Tests';
        $folder = 'Data';

        $this->copyOrMove($from, $to, $folder);

        /** initialise call */
        $this->adapter_name = 'Local';
        $this->action       = 'Delete';

        $this->options = array(
            'delete_empty' => false
        );

        return;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $this->path = BASE_FOLDER . '/Tests/Data';
        $this->delete($this->path);
    }

    /**
     * Get the file size of a given file or for the aggregate number of bytes in all directory files
     *
     * Recursive handling of files - uses file arrays created in Discovery
     *
     * $recursive  bool  For directory, recursively calculate file calculations default true
     *
     * @param   $path
     *
     * @return  int
     * @since   1.0
     */
    public function calculateSize($path, $recursive = true)
    {
        $size = 0;

        $this->discovery($path);

        foreach ($this->files as $file) {
            $size = $size + filesize($file);
        }

        return $size;
    }

    /**
     * Recursive Copy or Delete - uses Directory and File arrays created in Discovery
     *
     * Copy uses Directory array first to create folders, then copies the files
     *
     * @param   string  $path
     * @param   string  $target
     * @param   string  $target_name
     * @param   string  $copyOrMove
     *
     * @return  void
     * @since   1.0
     */
    function copyOrMove($path, $target, $target_name = '', $copyOrMove = 'copy')
    {
        if (file_exists($path)) {
        } else {
            return;
        }

        if (file_exists($target)) {
        } else {
            return;
        }

        $new_path = $target . '/' . $target_name;

        $this->discovery($path);

        if (count($this->directories) > 0) {
            asort($this->directories);
            foreach ($this->directories as $directory) {

                $new_directory = $new_path . '/' . substr($directory, strlen($path), 99999);

                if (basename($new_directory) == '.' || basename($new_directory) == '..' ) {

                } elseif (file_exists($new_directory)) {

                } else {
                    mkdir($new_directory);
                }
            }
        }

        if (count($this->files) > 0) {
            foreach ($this->files as $file) {
                $new_file = $new_path . '/' . substr($file, strlen($path), 99999);
                \copy($file, $new_file);
            }
        }

        if ($copyOrMove == 'move') {

            if (count($this->files) > 0) {
                foreach ($this->files as $file) {
                    unlink($file);
                }
            }

            if (count($this->directories) > 0) {
                arsort($this->directories);
                foreach ($this->directories as $directory) {
                    if (basename($directory) == '.' || basename($directory) == '..' ) {
                    } else {
                        rmdir($directory);
                    }
                }
            }
        }

        return;
    }

    /**
     * Recursive Delete, uses discovery Directory and File arrays to first delete files
     *  and then remove the folders
     *
     * @param   $path
     *
     * @return  int
     * @since   1.0
     */
    function delete($path)
    {
        if (file_exists($path)) {
        } else {
            return;
        }

        $this->discovery($path);

        if (count($this->files) > 0) {
            foreach ($this->files as $file) {
                unlink($file);
            }
        }

        if (count($this->directories) > 0) {

            arsort($this->directories);

            foreach ($this->directories as $directory) {

                if (basename($directory) == '.' || basename($directory) == '..' ) {
                } else {
                    rmdir($directory);
                }
            }
        }

        return;
    }

    /**
     * Discovery retrieves folder and file names, useful for other file/folder operations
     *
     * @param   $path
     *
     * @return  void
     * @since   1.0
     */
    public function discovery($path)
    {
        $this->directories = array();
        $this->files       = array();

        if (is_file($path)) {
            $this->files[] = $path;
            return;
        }

        if (is_dir($path)) {
        } else {
            return;
        }

        $this->directories[] = $path;

        $objects = new RecursiveIteratorIterator (
            new RecursiveDirectoryIterator($path),
            RecursiveIteratorIterator::SELF_FIRST);

        foreach ($objects as $name => $object) {

            if (is_file($name)) {
                $this->files[] = $name;

            } elseif (is_dir($name)) {

                if (basename($name) == '.' || basename($name) == '..' ) {
                } else {
                    $this->directories[] = $name;
                }
            }
        }

        return;
    }
}

