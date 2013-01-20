<?php
/**
 * Adapter Interface for Filesystem
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem\Api;

defined ('MOLAJO') or die;

/**
 * Describes an adapter instance for the filesystem
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
interface Adapter
{
    /**
     * Connect
     *
     * @return  null
     * @since   1.0
     */
    function connect ();

    /**
     * Close the FTP Connection
     *
     * @return  null
     * @since   1.0
     */
    function close ();

    /**
     * Get the Connection
     *
     * @return  null
     * @since   1.0
     */
    function getConnection ();

    /**
     * Set the Connection
     *
     * @return  null
     * @since   1.0
     */
    function setConnection ();

    /**
     * Returns the contents of the file identified in path
     *
     * @param   string  $path
     *
     * @return  null;
     * @since   1.0
     */
    public function read ($path);

    /**
     * Returns a list of file and folder names located at path directory
     *
     * @param   string  $path
     *
     * @return  null
     * @since   1.0
     */
    public function getList ($path);

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
    public function createDirectory ($path, $new_name, $replace = false, $create_directories = true);

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
    public function write ($path, $file, $data, $replace = false, $create_directories = true);

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
    public function copy ($path, File $target, $target_directory, $replace = false, $create_directories = true);

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
     * @return  mixed
     * @since   1.0
     */
    public function move ($path, File $target, $target_directory, $replace = false, $create_directories = true);

    /**
     * Deletes the file identified in path. Empty directories are removed, if so indicated.
     *
     * @param   string  $path
     * @param   bool    $delete_empty_directory
     *
     * @return  null
     * @since   1.0
     */
    public function delete ($path, $delete_empty_directory = true);

    /**
     * Retrieves metadata for the file specified in path and returns an associative array
     *  minimally populated with: last_accessed_date, last_updated_date, size, mimetype,
     *  absolute_path, relative_path, filename, and file_extension.
     *
     * @return  null
     * @since   1.0
     */
    public function getMetadata ($path, $options);

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
    public function setMetadata ($path, $options);
}
