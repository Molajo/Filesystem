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

use Molajo\Filesystem\Access\File as File;

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
        if (isset($this->options['name'])) {
            $this->filename = $this->options['name'];
        }

        return;
    }

    /**
     * Returns the contents of the file located at path directory
     *
     * @param   string  $path
     *
     * @return  mixed;
     * @since   1.0
     * @throws  \Exception\FileNotFound when file does not exist
     * @throws  \RuntimeException when unable to read file
     */
    public function read ($path)
    {
        $this->path = $this->adapter->normalise ($path);

        $this->adapter->exists ($key);

        $this->adapter->isFile ($key);

        if (file_exists ($this->path)) {
            return file_get_contents ($this->path);
        }
        if (false === $content) {
            throw new \RuntimeException(sprintf ('Could not read the "%s" key content.', $key));
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


        $numBytes = $this->adapter->write ($key, $content);

        if (false === $numBytes) {
            throw new \RuntimeException(sprintf ('Could not write the "%s" key content.', $key));
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
}
