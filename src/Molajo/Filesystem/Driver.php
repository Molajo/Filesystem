<?php
/**
 * Filesystem Driver
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem;

defined ('MOLAJO') or die;

use Molajo\Filesystem\Api\Adapter as Adapter;

use \RuntimeException;
use Molajo\Filesystem\FileNotFoundException;

/**
 * Filesystem Driver
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
Class Driver implements Adapter
{
    /**
     * Input options
     *
     * @var    array
     * @since  1.0
     */
    protected $options;

    /**
     * Construct
     *
     * @param   array  $options
     *
     * @since   1.0
     */
    public function __construct ($options)
    {
        $this->options = $options;

        if (isset($this->options['path'])) {
            $this->path = $this->options['path'];
        }

        if (isset($this->options['root'])) {
            $this->setRoot ($this->options['root']);
        } else {
            $this->setRoot ('/');
        }

        if (isset($this->options['persistence'])) {
            $this->setPersistence ($this->options['persistence']);
        } else {
            $this->setPersistence (0);
        }

        return;
    }

    /**
     * Get Root of Filesystem
     *
     * @return  null
     * @since   1.0
     */
    public function getRoot ()
    {
        return $this->root;
    }

    /**
     * Set Root of Filesystem
     *
     * @param   string  $root
     *
     * @return  mixed
     * @since   1.0
     */
    public function setRoot ($root)
    {
        return $this->root = rtrim ($root, '/\\') . '/';
    }

    /**
     * Set persistence indicator for Filesystem
     *
     * @param   bool  $persistence
     *
     * @return  null
     * @since   1.0
     */
    public function setPersistence ($persistence)
    {
        return $this->persistence = $persistence;
    }

    /**
     * Get persistence indicator for Filesystem
     *
     * @return  null
     * @since   1.0
     */
    public function getPersistence ()
    {
        return $this->persistence;
    }


    /**
     * Connect
     *
     * @return  null
     * @since   1.0
     */
    function connect ()
    {

    }

    /**
     * Close the FTP Connection
     *
     * @return  null
     * @since   1.0
     */
    function close ()
    {

    }

    /**
     * Get the Connection
     *
     * @return  null
     * @since   1.0
     */
    function getConnection ()
    {

    }

    /**
     * Set the Connection
     *
     * @return  null
     * @since   1.0
     */
    function setConnection ()
    {

    }

    /**
     * Returns the contents of the file located at path directory
     *
     * @param   string  $path
     *
     * @return  mixed;
     * @since   1.0
     * @throws  FileNotFound when file does not exist
     * @throws  RuntimeException when unable to read file
     */
    public function read ($path)
    {
        $this->path = $this->adapter->normalise ($path);

        $this->adapter->exists ($path);

        $this->adapter->isFile ($path);

        if (file_exists ($this->path)) {
            $data = file_get_contents ($this->path);
        }

        if (false === $data) {
            throw new \RuntimeException('Could not read: ', $path);
        }

        return false;
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
        $this->path = $this->normalise ($path);

        if (file_exists ($this->path)) {
            return file_get_contents ($this->path);
        }

        return false;
    }

    /**
     * Creates directory identified in path using the data value
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
    public function createDirectory ($path, $new_name, $replace = false,  $create_directories = true)
    {
        $this->path = $this->normalise ($path);

        if ($replace === false) {
            if (file_exists ($this->path)) {
                return false;
            }
        }

        if ($create_directories === false) {
            if (file_exists ($this->path)) {
            } else {
                return false;
            }
        }

        if (filer_exists ($this->path)) {
            return file_get_contents ($this->path);
        }

        \file_put_contents ($this->path, $data);

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
    public function write ($path, $data, $replace = false, $create_directories = true)
    {
        $this->path = $this->normalise ($path);

        if ($replace === false) {
            if (file_exists ($this->path)) {
                return false;
            }
        }

        if ($create_directories === false) {
            if (file_exists ($this->path)) {
            } else {
                return false;
            }
        }

        if (file_exists ($this->path)) {
            return file_get_contents ($this->path);
        }

        \file_put_contents ($this->path, $data);


        $numBytes = $this->adapter->write ($path, $data);

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
     * Note: File $target is an instance of the Filesystem exclusive to the target portion of the copy
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
    public function copy ($path, File $target, $target_directory, $replace = false, $create_directories = true)
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
    public function move ($path, File $target, $target_directory, $replace = false, $create_directories = true)
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
        $this->path = $this->normalise ($path);

        if (file_exists ($this->path)) {
            return unlink ($this->path);
        }

        return false;
    }

    /**
     * Retrieves metadata for the file specified in path and returns an associative array
     *  minimally populated with: last_accessed_date, last_updated_date, size, mimetype,
     *  absolute_path, relative_path, filename, and file_extension.
     *
     * @return  null
     * @since   1.0
     */
    public function getMetadata ()
    {

    }


    /**
     * Retrieves metadata for the file specified in path and returns an associative array
     *  minimally populated with: last_accessed_date, last_updated_date, size, mimetype,
     *  absolute_path, relative_path, filename, and file_extension.
     *
     * @param   string  $path
     *
     * @return  null
     * @since   1.0
     */
    public function setMetadata ()
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

}
