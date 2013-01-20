<?php
/**
 * Filesystem for the Application
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem\Api;

defined ('MOLAJO') or die;

/**
 * Describes a filesystem instance for the application
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
Interface Filesystem
{

    /**
     * Returns the contents of the file located at path directory
     *
     * @param   string  $path
     *
     * @return  null
     * @since   1.0
     */
    public function read ($path);


    /**
     * Renames the file identified in path to new_name within the existing parent directory
     *
     * @param   string  $path
     * @param   string  $new_name
     *
     * @return  null
     * @since   1.0
     */
    public function getList ($path);

    /**
     * Creates or replaces the file identified in path using the data value
     *
     * @param   string  $path
     * @param           $data
     * @param   bool    $replace
     * @param   bool    $create_directories
     *
     * @return  null
     * @since   1.0
     */
    public function write ($path, $data, $replace = false, $create_directories = true);

    /**
     * Copies the file identified in $path to the new_parent_directory,
     *  replacing existing contents, if indicated, and creating directories needed, if indicated
     *
     * @param   string     $path
     * @param   File       $target
     * @param   string     $target_directory
     * @param   bool       $replace
     * @param   bool       $create_directories
     *
     * @return  mixed
     * @since   1.0
     */
    public function copy ($path, File $target, $target_directory,
        $replace = false, $create_directories = true);

    /**
     * Moves the file identified in path to the location identified in the new_parent_directory
     *  replacing existing contents, if indicated, and creating directories needed, if indicated
     *
     * @param   string  $path
     * @param   string  $new_parent_directory
     * @param   bool    $replace
     * @param   bool    $create_directories
     *
     * @return  null
     * @since   1.0
     */
    public function move ($path, $new_parent_directory, $replace = false, $create_directories = true);

    /**
     * Deletes the file identified in path. Empty directories are removed if so indicated
     *
     * @param   string  $path
     * @param   bool    $delete_empty_directory
     *
     * @return  null
     * @since   1.0
     */
    public function delete ($path, $delete_empty_directory = true);
}
