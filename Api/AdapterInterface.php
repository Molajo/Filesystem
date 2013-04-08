<?php
/**
 * Filesystem Adapter Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Filesystem\Api;

defined('MOLAJO') or die;

use Molajo\Filesystem\Exception\FilesystemException;

/**
 * Filesystem Adapter Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @since     1.0
 * @api
 */
interface AdapterInterface
{
    /**
     * Determines if file or folder identified in path exists
     *
     * @param   string $path
     *
     * @return  bool
     * @since   1.0
     * @throws  FilesystemException
     * @api
     */
    public function exists($path);

    /**
     * Returns an associative array of metadata for the file or folder specified in path
     *
     * @param   string $path
     *
     * @return  mixed
     * @since   1.0
     * @throws  FilesystemException
     * @api
     */
    public function getMetadata($path);

    /**
     * Returns the contents of the file identified in path
     *
     * @param   string $path
     *
     * @return  null|string
     * @since   1.0
     * @throws  FilesystemException
     * @api
     */
    public function read($path);

    /**
     * Returns a list of file and folder names located at path directory, optionally recursively
     *
     * @param   string $path
     * @param   bool   $recursive
     * @param   string $extensions
     *
     * @return  mixed
     * @since   1.0
     * @throws  FilesystemException
     * @api
     */
    public function getList($path, $recursive = false, $extensions = '');

    /**
     * Creates (or replaces) the file or creates the directory identified in path;
     *
     * @param   string $path
     * @param   string $data    file, only
     * @param   bool   $replace file, only
     *
     * @return  $this
     * @since   1.0
     * @throws  FilesystemException
     * @api
     */
    public function write($path, $data = '', $replace = true);

    /**
     * Deletes the file or folder identified in path, optionally deletes recursively
     *
     * @param   string $path
     * @param   bool   $recursive
     *
     * @return  $this
     * @since   1.0
     * @throws  FilesystemException
     * @api
     */
    public function delete($path, $recursive = false);

    /**
     * Copies the file/folder in $path to the target_directory (optionally target_name),
     *  replacing content, if indicated. Can copy to target_filesystem_type.
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
     * @api
     */
    public function copy(
        $path,
        $target_directory,
        $target_name = '',
        $replace = true,
        $target_filesystem_type = ''
    );

    /**
     * Moves the file/folder in $path to the target_directory (optionally target_name),
     *  replacing content, if indicated. Can move to target_filesystem_type.
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
     * @api
     */
    public function move(
        $path,
        $target_directory,
        $target_name = '',
        $replace = true,
        $target_filesystem_type = ''
    );

    /**
     * Rename file or folder identified in path
     *
     * @param   string $path
     * @param   string $new_name
     *
     * @return  $this
     * @since   1.0
     * @throws  FilesystemException
     * @api
     */
    public function rename($path, $new_name);

    /**
     * Change owner for file or folder identified in path
     *
     * @param   string $path
     * @param   string $user_name
     * @param   bool   $recursive
     *
     * @return  $this
     * @since   1.0
     * @throws  FilesystemException
     * @api
     */
    public function changeOwner($path, $user_name, $recursive = false);

    /**
     * Change group for file or folder identified in path
     *
     * @param   string $path
     * @param   string $group_id
     * @param   bool   $recursive
     *
     * @return  $this
     * @since   1.0
     * @throws  FilesystemException
     * @api
     */
    public function changeGroup($path, $group_id, $recursive = false);

    /**
     * Change permissions for file or folder identified in path
     *
     * @param   string $path
     * @param   int    $permission
     * @param   bool   $recursive
     *
     * @return  $this
     * @since   1.0
     * @throws  FilesystemException
     * @api
     */
    public function changePermission($path, $permission, $recursive = false);

    /**
     * Update the modification time and access time (touch) for the directory or file identified in the path
     *
     * @param   string $path
     * @param   int    $modification_time
     * @param   int    $access_time
     * @param   bool   $recursive
     *
     * @return  $this
     * @since   1.0
     * @throws  FilesystemException
     * @api
     */
    public function touch($path, $modification_time = null, $access_time = null, $recursive = false);
}
