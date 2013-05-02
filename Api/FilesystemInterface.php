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

use Molajo\Filesystem\Exception\AdapterException;

/**
 * Filesystem Adapter Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @since     1.0
 * @api
 */
interface FilesystemInterface
{
    /**
     * Determines if file or folder identified in path exists
     *
     * @param   string $path
     *
     * @return  bool
     * @since   1.0
     * @throws  AdapterException
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
     * @throws  AdapterException
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
     * @throws  AdapterException
     * @api
     */
    public function read($path);

    /**
     * Returns a list of file and folder names located at path directory, optionally recursively,
     *  optionally filtered by a list of file extension values, filename mask, and inclusion or exclusion
     *  of files and/or folders
     *
     * @param   string $path
     * @param   bool   $recursive
     * @param   string $extension_list
     * @param   bool   $include_files
     * @param   bool   $include_folders
     * @param   null   $filename_mask
     *
     * @return  mixed
     * @since   1.0
     * @throws  AdapterException
     * @api
     */
    public function getList($path, $recursive = false, $extension_list = '',
        $include_files = false, $include_folders = false, $filename_mask = null);

    /**
     * Creates (or replaces) the file or creates the directory identified in path;
     *
     * @param   string $path
     * @param   string $data     (file, only)
     * @param   bool   $replace  (file, only)
     * @param   bool   $append   (file, only)
     * @param   bool   $truncate (file, only)
     *
     * @return  $this
     * @since   1.0
     * @throws  AdapterException
     * @api
     */
    public function write($path, $data = '', $replace = true, $append = false, $truncate = false);

    /**
     * Deletes the file or folder identified in path, optionally deletes recursively
     *
     * @param   string $path
     * @param   bool   $recursive
     *
     * @return  $this
     * @since   1.0
     * @throws  AdapterException
     * @api
     */
    public function delete($path, $recursive = false);

    /**
     * Copies the file/folder in $path to the target_directory (optionally target_name),
     *  replacing content, if indicated. Can copy to target_handler.
     *
     * @param   string $path
     * @param   string $target_directory
     * @param   string $target_name
     * @param   bool   $replace
     * @param   string $target_handler
     *
     * @return  $this
     * @since   1.0
     * @throws  AdapterException
     * @api
     */
    public function copy(
        $path,
        $target_directory,
        $target_name = '',
        $replace = true,
        $target_handler = ''
    );

    /**
     * Moves the file/folder in $path to the target_directory (optionally target_name),
     *  replacing content, if indicated. Can move to target_handler.
     *
     * @param   string $path
     * @param   string $target_directory
     * @param   string $target_name
     * @param   bool   $replace
     * @param   string $target_handler
     *
     * @return  $this
     * @since   1.0
     * @throws  AdapterException
     * @api
     */
    public function move(
        $path,
        $target_directory,
        $target_name = '',
        $replace = true,
        $target_handler = ''
    );

    /**
     * Rename file or folder identified in path
     *
     * @param   string $path
     * @param   string $new_name
     *
     * @return  $this
     * @since   1.0
     * @throws  AdapterException
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
     * @throws  AdapterException
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
     * @throws  AdapterException
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
     * @throws  AdapterException
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
     * @throws  AdapterException
     * @api
     */
    public function touch($path, $modification_time = null, $access_time = null, $recursive = false);
}
