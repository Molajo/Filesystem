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
     * @return mixed|string|array
     * @since   1.0
     */
    public function read();

    /**
     * Returns a list of file and folder names located at path directory
     *
     * @param bool $recursive
     *
     * @return array
     * @since   1.0
     */
    public function getList($recursive = false);

    /**
     * Creates or replaces the file or directory identified in path using the data value
     *
     * @param string $file
     * @param bool   $replace
     * @param string $data
     *
     * @return void
     * @since   1.0
     */
    public function write($file = '', $replace = true, $data = '');

    /**
     * Deletes the file or folder identified in path. Deletes subdirectories, if so indicated
     *
     * @param bool $delete_subdirectories
     *
     * @return void
     * @since   1.0
     */
    public function delete($delete_subdirectories = true);

    /**
     * Copies the file/folder in $path to the target_directory, replacing content, if indicated
     *
     * Note: $target_filesystem_type used to create new filesystem instance for target
     *
     * @param string $target_directory
     * @param string $target_name
     * @param bool   $replace
     * @param string $target_filesystem_type
     *
     * @return mixed
     * @since   1.0
     */
    public function copy($target_directory, $target_name = '', $replace = true, $target_filesystem_type = '');

    /**
     * Moves the file/folder in $path to the target_directory, replacing content, if indicated
     *
     * Note: $target_filesystem_type used to create new filesystem instance for target
     *
     * @param string $target_directory
     * @param string $target_name
     * @param bool   $replace
     * @param string $target_filesystem_type
     *
     * @return mixed
     * @since   1.0
     */
    public function move($target_directory, $target_name = '', $replace = true, $target_filesystem_type = '');

    /**
     * Change owner for file or folder identified in path
     *
     * @param string $user_name
     *
     * @return void
     * @since   1.0
     */
    public function changeOwner($user_name);

    /**
     * Change group for file or folder identified in path
     *
     * @param string $group_id
     *
     * @return void
     * @since   1.0
     */
    public function changeGroup($group_id);

    /**
     * Change permissions for file or folder identified in path
     *
     * @param int $permission
     *
     * @return void
     * @since   1.0
     */
    public function changePermission($permission);

    /**
     * Update the modification time and access time (touch) for the directory or file identified in the path
     *
     * @param null $modification_time
     * @param null $access_time
     *
     * @return void
     * @since   1.0
     */
    public function touch($modification_time = null, $access_time = null);
}
