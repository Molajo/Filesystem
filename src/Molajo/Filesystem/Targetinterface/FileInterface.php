<?php
/**
 * File Interface for Filesystem Adapter
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem\Targetinterface;

defined('MOLAJO') or die;

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
     * Connect to the Filesystem
     *
     * @param   string  Filesystem $type
     *
     * @return  object  Filesystem
     * @since   1.0
     */
    public function connect($type);

    /**
     * Sets the value of the path defining the current directory and file
     *
     * @param   $path
     *
     * @return  string
     * @since   1.0
     */
    public function setPath($path);

    /**
     * Retrieves and sets metadata for the file specified in path
     *
     * @return  object  Filesystem
     * @since   1.0
     */
    public function getMetadata();

    /**
     * Returns the contents of the file identified in path
     *
     * @return  mixed|string|array
     * @since   1.0
     */
    public function read();

    /**
     * Creates or replaces the file or directory identified in path using the data value
     *
     * @param   string  $file       spaces for create directory
     * @param   bool    $replace
     * @param   string  $data       spaces for create directory
     *
     * @return  null
     * @since   1.0
     */
    public function write($file = '', $replace = true, $data = '');

    /**
     * Deletes the file or folder identified in path. Deletes subdirectories, if so indicated
     *
     * @param   bool    $delete_subdirectories  defaults true (for directories)
     *
     * @return  null
     * @since   1.0
     */
    public function delete($delete_subdirectories = true);

    /**
     * Copies the file/folder in $path to the target_directory, replacing content, if indicated
     *
     * Note: $target_filesystem_type used to create new filesystem instance for target
     *
     * @param   string  $target_directory
     * @param   bool    $replace                 defaults to true
     * @param   string  $target_filesystem_type  defaults to current
     *
     * @return  void
     * @since   1.0
     */
    public function copy($target_directory, $replace = true, $target_filesystem_type = '');

    /**
     * Moves the file/folder in $path to the target_directory, replacing content, if indicated
     *
     * Note: $target_filesystem_type used to create new filesystem instance for target
     *
     * @param   string  $target_directory
     * @param   bool    $replace                 defaults to true
     * @param   string  $target_filesystem_type  defaults to current
     *
     * @return  void
     * @since   1.0
     */
    public function move($target_directory, $replace = true, $target_filesystem_type = '');

    /**
     * Returns a list of file and folder names located at path directory
     *
     * @param   bool  $recursive
     *
     * @return  array
     * @since   1.0
     */
    public function getList($recursive = false);

    /**
     * Change file mode
     *
     * @param   string  $mode
     *
     * @return  void
     * @since   1.0
     */
    public function chmod($mode = '');

    /**
     * Update the touch time and/or the access time for the directory or file identified in the path
     *
     * @param   null    $time
     * @param   null    $atime
     *
     * @return  void
     * @since   1.0
     */
    public function touch($time = null, $atime = null);

    /**
     * Close the Connection
     *
     * @return  void
     * @since   1.0
     */
    public function close();
}
