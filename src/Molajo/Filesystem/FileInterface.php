<?php
/**
 * Basic Adapter Interface for Filesystem
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem;

defined ('MOLAJO') or die;

/**
 * Basic adapter connect instance for the filesystem
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
interface FileInterface
{
    /**
     * Returns the value 'directory', 'file' or 'link' for the type determined
     *  from the path
     *
     * @param   string  $path
     *
     * @return  null
     * @since   1.0
     */
    function getType ($path);

    /**
     * Determine if the file or directory specified in path exists
     *
     * @param   string  $path
     *
     * @return  null
     * @since   1.0
     */
    function exists ($path);

    /**
     * Returns the contents of the file identified in path
     *
     * @param   string  $path
     *
     * @return  null;
     * @since   1.0
     */
    function read ($path = '');

    /**
     * Returns a list of file and folder names located at path directory
     *
     * @param   string  $path
     *
     * @return  null
     * @since   1.0
     */
    function getList ($path);

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
    function createDirectory ($path, $new_name, $replace = false, $create_directories = true);

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
    function write ($path, $file, $data, $replace = false, $create_directories = true);

    /**
     * Copies the file identified in $path to the target_adapter in the new_parent_directory,
     *  replacing existing contents, if indicated, and creating directories needed, if indicated
     *
     * Note: $target_filesystem is an instance of the Filesystem exclusive to the target portion of the copy
     *
     * @param      $path
     * @param      $target_filesystem
     * @param      $target_directory
     * @param bool $replace
     * @param bool $create_directories
     *
     * @return  null
     * @since   1.0
     */
    function copy ($path, $target_filesystem, $target_directory, $replace = false, $create_directories = true);

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
    function move ($path, $target_filesystem, $target_directory, $replace = false, $create_directories = true);

    /**
     * Deletes the file identified in path. Empty directories are removed, if so indicated.
     *
     * @param   string  $path
     * @param   bool    $delete_empty_directory
     *
     * @return  null
     * @since   1.0
     */
    function delete ($path, $delete_empty_directory = true);

    /**
     * Retrieves metadata for the file specified in path and returns an associative array
     *  minimally populated with: last_accessed_date, last_updated_date, size, mimetype,
     *  absolute_path, relative_path, filename, and file_extension.
     *
     * @return  null
     * @since   1.0
     */
    function getMetadata ($path, $options);

    /**
     * Normalizes the given path
     *
     * @param   $path
     *
     * @return  string
     * @since   1.0
     */
    function normalise ($path);
}
