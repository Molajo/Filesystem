<?php
/**
 * File Interface for Filesystem Adapter
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem\Targetinterface;

defined ('MOLAJO') or die;

/**
 * File Interface for Filesystem Adapter
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
interface FileInterface
{
    /**
     * Returns the contents of the file identified in path
     *
     * @param   string  $path
     *
     * @return  array
     * @since   1.0
     */
    public function read ($path = '');

    /**
     * Creates or replaces the file identified in path using the data value
     *
     * @param   string  $path
     * @param   string  $file
     * @param   string  $data
     * @param   bool    $replace
     *
     * @return  null
     * @since   1.0
     */
    public function write ($path = '', $file, $data, $replace);

    /**
     * Deletes the file identified in path. Empty directories are removed, if so indicated.
     *
     * @param   string  $path
     * @param   bool    $delete_empty_directory
     *
     * @return  null
     * @since   1.0
     */
    public function delete ($path = '', $delete_empty_directory = true);

    /**
     * Copies the file identified in $path to the target_adapter in the new_parent_directory,
     *  replacing existing contents, if indicated, and creating directories needed, if indicated
     *
     * Note: $target_filesystem is an instance of the Filesystem exclusive to the target portion of the copy
     *
     * @param   string  $path
     * @param   string  $target_filesystem
     * @param   string  $target_directory
     * @param   bool    $replace
     *
     * @return  null
     * @since   1.0
     */
    public function copy ($path = '', $target_filesystem, $target_directory, $replace = false);

    /**
     * Moves the file identified in path to the location identified in the new_parent_directory
     *  replacing existing contents, if indicated, and creating directories needed, if indicated
     *
     * @param   string  $path
     * @param   string  $target_filesystem
     * @param   string  $target_directory
     * @param   bool    $replace
     *
     * @return  mixed
     * @since   1.0
     */
    public function move ($path = '', $target_filesystem, $target_directory, $replace = false);

    /**
     * Returns a list of file and folder names located at path directory
     *
     * @param   string  $path
     *
     * @return  null
     * @since   1.0
     */
    public function getList ($path = '');

    /**
     * Creates directory identified in path using the data value
     *
     * @param   string  $path
     * @param   string  $new_name
     * @param   bool    $replace
     *
     * @return  null
     * @since   1.0
     */
    public function createDirectory ($path = '', $new_name, $replace = false);

    /**
     * Delete directory identified in path using the data value
     *
     * @param   string  $path
     * @param   bool    $delete_subdirectories
     *
     * @return  null
     * @since   1.0
     */
    public function deleteDirectory ($path = '', $delete_subdirectories = true);

}
