<?php
/**
 * Actions Interface for Filesystem
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Filesystem\Adapter;

defined('MOLAJO') or die;

use Molajo\Filesystem\Exception\FilesystemException;

/**
 * Actions Interface for Filesystem
 *
 * Further defines the doAction method in AdapterInterface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @since     1.0
 */
interface ActionsInterface
{
    /**
     * Returns the contents of the file identified in path
     *
     * @param   string $path
     *
     * return array|mixed|object|string
     *
     * @since   1.0
     * @throws  FilesystemException
     */
    public function read($path);

    /**
     * Returns a list of file and folder names located at path directory
     *
     * @param   string $path
     * @param   bool   $recursive
     *
     * @return  $this
     * @since   1.0
     * @throws  FilesystemException
     */
    public function getList($path, $recursive = false);

    /**
     * Creates or replaces the file or directory identified in path using the data value
     *
     * @param   string $path
     * @param   string $file
     * @param   bool   $replace
     * @param   string $data
     *
     * @return  $this
     * @since   1.0
     * @throws  FilesystemException
     */
    public function write($path, $file = '', $replace = true, $data = '');

    /**
     * Deletes the file or folder identified in path. Deletes subdirectories, if so indicated
     *
     * @param   string $path
     * @param   bool   $delete_subdirectories
     *
     * @return  $this
     * @since   1.0
     * @throws  FilesystemException
     */
    public function delete($path, $delete_subdirectories = true);

    /**
     * Copies the file/folder in $path to the target_directory, replacing content, if indicated
     *
     * Note: $target_filesystem_type used to create new filesystem instance for target
     *
     * @param   string $path
     * @param   string $target_directory
     * @param   string $target_name
     * @param   bool   $replace
     * @param   string $target_filesystem_type
     *
     * @return  $this
     * @since   1.0
     * @throws  FilesystemException
     */
    public function copy(
        $path,
        $target_directory,
        $target_name = '',
        $replace = true,
        $target_filesystem_type = ''
    );

    /**
     * Moves the file/folder in $path to the target_directory, replacing content, if indicated
     *
     * Note: $target_filesystem_type used to create new filesystem instance for target
     *
     * @param   string $path
     * @param   string $target_directory
     * @param   string $target_name
     * @param   bool   $replace
     * @param   string $target_filesystem_type
     *
     * @return  $this
     * @since   1.0
     * @throws  FilesystemException
     */
    public function move(
        $path,
        $target_directory,
        $target_name = '',
        $replace = true,
        $target_filesystem_type = ''
    );

    /**
     * Change owner for file or folder identified in path
     *
     * @param   string $path
     * @param   string $user_name
     *
     * @return  $this
     * @since   1.0
     * @throws  FilesystemException
     */
    public function changeOwner($path, $user_name);

    /**
     * Change group for file or folder identified in path
     *
     * @param   string $path
     * @param   string $group_id
     *
     * @return  $this
     * @since   1.0
     * @throws  FilesystemException
     */
    public function changeGroup($path, $group_id);

    /**
     * Change permissions for file or folder identified in path
     *
     * @param   string $path
     * @param   int    $permission
     *
     * @return  $this
     * @since   1.0
     * @throws  FilesystemException
     */
    public function changePermission($path, $permission);

    /**
     * Update the modification time and access time (touch) for the directory or file identified in the path
     *
     * @param   string $path
     * @param   int    $modification_time
     * @param   int    $access_time
     *
     * @return  $this
     * @since   1.0
     * @throws  FilesystemException
     */
    public function touch($path, $modification_time = null, $access_time = null);
}
