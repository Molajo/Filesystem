<?php
/**
 * File Instance for Filesystem
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem\Access;

defined ('MOLAJO') or die;

use Molajo\Filesystem\File as FileInterface;

use Molajo\Filesystem\Adapter;

/**
 * Describes a file instance for Filesystem
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
Class File extends Directory implements FileInterface
{
    /**
     * File Name
     *
     * @var    Adapter
     * @since  1.0
     */
    protected $filename;

    /**
     * Construct
     *
     * @param   Adapter     $adapter
     * @param   Path        $path
     * @param   array       $options
     *
     * @since   1.0
     */
    public function __construct (Adapter $adapter, $path, $options = array())
    {
        if (isset($this->options['name'])) {
            $this->filename = $this->options['name'];
        }

        return;
    }

    /**
     * Sets the name of the file specified in the path
     *
     * @param   string  $path
     *
     * @return  null
     * @since   1.0
     */
    public function setFilename ()
    {

    }

    /**
     * Retrieves the name of the file specified in the path
     *
     * @return  null
     * @since   1.0
     */
    public function getName ()
    {
        return $this->filename;
    }

    /**
     * Retrieves the extension type for the file identified in the path
     *
     * @return  null
     * @since   1.0
     */
    public function getExtension ()
    {
        return pathinfo ($this->path, PATHINFO_EXTENSION);
    }

    /**
     * Sets the extension type for the file identified in the path
     *
     * @param   string $path
     *
     * @return  null
     * @since   1.0
     */
    public function setExtension ($path)
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
    public function setMetadata ($path)
    {

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
     * Returns the contents of the file located at path directory
     *
     * @param   string  $path
     *
     * @return  mixed;
     * @since   1.0
     */
    public function read ($path)
    {
        $this->path = $this->normalise ($path);

        if (file_exists ($this->path)) {
            return file_get_contents ($this->path);
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
        $this->path = $this->normalise ($path);

        if (file_exists ($this->path)) {
            return unlink ($this->path);
        }

        return false;
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
