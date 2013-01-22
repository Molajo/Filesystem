<?php
/**
 * Local Adapter for Filesystem
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem\Adapter;

defined ('MOLAJO') or die;

use Molajo\Filesystem\FilesystemAdapter;

use Exception;
use Molajo\Filesystem\Exception\FileException;

/**
 * Local Adapter for Filesystem
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class Local extends FilesystemAdapter
{
    /**
     * Constructor
     *
     * @param  string  $path
     * @param  array   $options
     *
     * @since  1.0
     */
    public function __construct ($path, $options = array())
    {
        parent::__construct ($path, $options);

        return;
    }

    /**
     * Does the path exist (either as a file or a folder)?
     *
     * @param string $path
     *
     * @return bool|null
     */
    public function exists ($path)
    {
        return parent::exists ($path);
    }

    /**
     * Returns the value 'directory', 'file' or 'link' for the type determined
     *  from the path
     *
     * @param   string  $path
     *
     * @return  null
     * @since   1.0
     */
    function getType ($path)
    {
        return parent::exists ($path);
    }

    /**
     * Returns the contents of the file located at path directory
     *
     * @param   string  $path
     *
     * @return  mixed
     * @since   1.0
     * @throws  FileException when file does not exist
     * @throws  /RuntimeException when unable to read file
     */
    public function read ($path = '')
    {
        if ($path == '') {
            $path = $this->path;
        }

        return parent::read ($path);
    }

    /**
     * Returns the contents of the file located at path directory
     *
     * @param   string  $path
     *
     * @return  mixed;
     * @since   1.0
     */
    public function getList ($path)
    {
        $path = $this->normalise ($path);

        if (file_exists ($path)) {
            return file_get_contents ($path);
        }

        return false;
    }

    /**
     * Creates directory identified in path using the data value
     *
     * @param   string  $path
     * @param   string  $new_name
     * @param   bool    $replace
     * @param   bool    $create_directories
     *
     * @return  null
     * @since   1.0
     */
    public function createDirectory ($path, $new_name, $replace = false, $create_directories = true)
    {
        $path = $this->normalise ($path);

        if ($replace === false) {
            if (file_exists ($path)) {
                return false;
            }
        }

        if ($create_directories === false) {
            if (file_exists ($path)) {
            } else {
                return false;
            }
        }

        if (file_exists ($path)) {
            return file_get_contents ($path);
        }

        \mk_dir ($path);

        // Desired folder structure
        $structure = './depth1/depth2/depth3/';

// To create the nested structure, the $recursive parameter
// to mkdir() must be specified.

        if (! mkdir ($structure, 0, true)) {
            die('Failed to create folders...');
        }


        return false;
    }

    /**
     * Creates or replaces the file identified in path using the data value
     *
     * @param   string  $path
     * @param   string  $file
     * @param           $data
     * @param   bool    $replace
     * @param   bool    $create_directories
     *
     * @return  null
     * @since   1.0
     */
    function write ($path, $file, $data, $replace = false, $create_directories = true)
    {
        $path = $this->normalise ($path);

        if ($replace === false) {
            if (file_exists ($path)) {
                return false;
            }
        }

        if ($create_directories === false) {
            if (file_exists ($path)) {
            } else {
                return false;
            }
        }

        if (file_exists ($path)) {
            return file_get_contents ($path);
        }

        \file_put_contents ($path, $data);


        $numBytes = $this->adapter->write ($path, $file, $data, $replace = false, $create_directories = true);

        if (false === $numBytes) {
            throw new \RuntimeException(sprintf ('Could not write the "%s" key content.', $path));
        }

        return $numBytes;

        return false;
    }

    /**
     * Copies the file identified in $path to the target_adapter in the new_parent_directory,
     *  replacing existing contents, if indicated, and creating directories needed, if indicated
     *
     * Note: $target_filesystem is an instance of the Filesystem exclusive to the target portion of the copy
     *
     * @param   string  $path
     * @param   File    $target
     * @param   string  $target_directory
     * @param   bool    $replace
     * @param   bool    $create_directories
     *
     * @return  null
     * @since   1.0
     */
    public function copy ($path, $target_filesystem, $target_directory, $replace = false, $create_directories = true)
    {
        $data = $this->read ($path);

        $target->write ($target_directory, $data, $replace, $create_directories);

        return;
    }

    /**
     * Moves the file identified in path to the location identified in the new_parent_directory
     *  replacing existing contents, if indicated, and creating directories needed, if indicated
     *
     * @param   string  $path
     * @param   File    $target
     * @param   string  $target_directory
     * @param   bool    $replace
     * @param   bool    $create_directories
     *
     * @return  null
     * @since   1.0
     */
    public function move ($path, $target_filesystem, $target_directory, $replace = false, $create_directories = true)
    {
        $data = $this->read ($path);

        $target->write ($target_directory, $data, $replace, $create_directories);

        $this->delete ($path);

        return;
    }

    /**
     * Deletes the file identified in path. Empty directories are removed if so indicated
     *
     * @param   string  $path
     * @param   bool    $delete_empty_directory
     *
     * @return  null
     * @since   1.0
     */
    public function delete ($path, $delete_empty_directory = true)
    {
        $path = $this->normalise ($path);

        if (file_exists ($path)) {
            return unlink ($path);
        }

        return false;
    }

    /**
     * Retrieves metadata for the file specified in path and returns an associative array
     *  minimally populated with: last_accessed_date, last_updated_date, size, mimetype,
     *  absolute_path, relative_path, filename, and file_extension.
     *
     * @param   string  $path
     * @param   string  $options
     *
     * @return  null
     * @since   1.0
     */
    public function getMetadata ($path, $options)
    {

    }

    //
    //  Helper methods
    //

    /**
     * Normalizes the given path
     *
     * @param   $path
     *
     * @return  string
     * @since   1.0
     */
    public function normalise ($path)
    {
        $absolute_path = false;
        if (substr ($path, 0, 1) == '/') {
            $absolute_path = true;
            $path          = substr ($path, 1, strlen ($path));
        }

        /** Unescape slashes */
        $path = str_replace ('\\', '/', $path);

        /**  Filter: empty value
         *
         * @link http://tinyurl.com/arrayFilterStrlen
         */
        $nodes = array_filter (explode ('/', $path), 'strlen');

        $normalised = array();

        foreach ($nodes as $node) {

            /** '.' means current - ignore it      */
            if ($node == '.') {

                /** '..' is parent - remove the parent */
            } elseif ($node == '..') {

                if (count ($normalised) > 0) {
                    array_pop ($normalised);
                }

            } else {
                $normalised[] = $node;
            }

        }

        $path = implode ('/', $normalised);
        if ($absolute_path === true) {
            $path = '/' . $path;
        }

        return $path;
    }

    /**
     * Get the file size of a given file.
     *
     * @param string $path
     *
     * @return int
     */
    public function size ($path)
    {
        return filesize ($path);
    }

    /**
     * Retrieves Create Date for directory or file identified in the path
     *
     * @return  null
     * @since   1.0
     */
    public function getCreateDate ()
    {

    }

    /**
     * Retrieves Last Access Date for directory or file identified in the path
     *
     * @return  null
     * @since   1.0
     */
    public function getAccessDate ()
    {

    }

    /**
     * Retrieves Last Update Date for directory or file identified in the path
     *
     * @return  null
     * @since   1.0
     */
    public function getUpdateDate ()
    {
        return filemtime ($this->path);
    }

    /**
     * Sets the Last Access Date for directory or file identified in the path
     *
     * @return  null
     * @since   1.0
     */
    public function setAccessDate ()
    {

    }

    /**
     * Sets the Last Update Date for directory or file identified in the path
     *
     * @param   string  $value
     *
     * @return  null
     * @since   1.0
     */
    public function setUpdateDate ($value)
    {

    }
}
