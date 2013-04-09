<?php
/**
 * Adapter for Filesystem
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Filesystem;

defined('MOLAJO') or die;

use Exception;
use Molajo\Filesystem\Exception\FilesystemException;
use Molajo\Filesystem\Api\ConnectionInterface;
use Molajo\Filesystem\Api\AdapterInterface;

/**
 * Adapter for Filesystem
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @since     1.0
 */
class Adapter implements ConnectionInterface, AdapterInterface
{
    /**
     * Filesystem Type
     *
     * @var     object
     * @since   1.0
     */
    public $fsType;

    /**
     * Constructor
     *
     * @param   AdapterInterface $filesystem
     * @param   array            $options
     *
     * @since   1.0
     */
    public function __construct(AdapterInterface $filesystem)
    {
        $this->fsType = $filesystem;
    }

    /**
     * Connect to the Filesystem Type
     *
     * @param   array $options
     *
     * @return  $this
     * @since   1.0
     * @throws  FilesystemException
     * @api
     */
    public function connect($options = array())
    {
        try {
            $this->fsType->connect($options);

        } catch (Exception $e) {

            throw new FilesystemException
            ('Filesystem: Caught Exception: ' . $e->getMessage());
        }

        return $this;
    }

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
    public function exists($path)
    {
        try {
            return $this->fsType->exists($path);

        } catch (Exception $e) {
            throw new FilesystemException('Filesystem: Exists Exception ' . $e->getMessage());
        }
    }

    /**
     * Returns an associative array of metadata for the file or folder specified in path
     *
     * @param   string $path
     *
     * @return  object
     * @since   1.0
     * @throws  FilesystemException
     * @api
     */
    public function getMetadata($path)
    {
        try {
            return $this->fsType->getMetadata($path);

        } catch (Exception $e) {
            throw new FilesystemException('Filesystem: getMetadata Exception ' . $e->getMessage());
        }
    }

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
    public function read($path)
    {
        try {

            return $this->fsType->read($path);

        } catch (Exception $e) {
            throw new FilesystemException('Filesystem: Read Exception ' . $e->getMessage());
        }
    }

    /**
     * Returns a list of file and folder names located at path directory, optionally recursively
     *
     * @param   string $path
     * @param   bool   $recursive
     * @param   string $extension_list
     * @param   bool   $include_files
     * @param   bool   $include_folders
     *
     * @return  mixed
     * @since   1.0
     * @throws  FilesystemException
     * @api
     */
    public function getList($path, $recursive = false, $extension_list = '',
        $include_files = false, $include_folders = false)
    {
        try {

            return $this->fsType->getList($path, $recursive, $extension_list, $include_files, $include_folders);

        } catch (Exception $e) {
            throw new FilesystemException('Filesystem: getList Exception ' . $e->getMessage());
        }
    }

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
     * @throws  FilesystemException
     * @api
     */
    public function write($path, $data = '', $replace = true, $append = false, $truncate = false)
    {
        try {

            return $this->fsType->write($path, $data, $replace, $append, $truncate);

        } catch (Exception $e) {
            throw new FilesystemException('Filesystem: Write Exception ' . $e->getMessage());
        }
    }

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
    public function delete($path, $recursive = false)
    {
        try {

            return $this->fsType->delete($path, $recursive);

        } catch (Exception $e) {
            throw new FilesystemException('Filesystem: Delete Exception ' . $e->getMessage());
        }
    }

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
    ) {
        try {

            return $this->fsType->copy($path, $target_directory, $target_name, $replace, $target_filesystem_type);

        } catch (Exception $e) {
            throw new FilesystemException('Filesystem: Copy Exception ' . $e->getMessage());
        }
    }

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
    ) {
        try {

            return $this->fsType->move($path, $target_directory, $target_name, $replace, $target_filesystem_type);

        } catch (Exception $e) {
            throw new FilesystemException('Filesystem: Move Exception ' . $e->getMessage());
        }
    }

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
    public function rename($path, $new_name)
    {
        try {

            return $this->fsType->rename($path, $new_name);

        } catch (Exception $e) {
            throw new FilesystemException('Filesystem: Rename Exception ' . $e->getMessage());
        }
    }

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
    public function changeOwner($path, $user_name, $recursive = false)
    {
        try {

            return $this->fsType->changeOwner($path, $user_name, $recursive);

        } catch (Exception $e) {
            throw new FilesystemException('Filesystem: changeOwner Exception ' . $e->getMessage());
        }
    }

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
    public function changeGroup($path, $group_id, $recursive = false)
    {
        try {

            return $this->fsType->changeGroup($path, $group_id, $recursive);

        } catch (Exception $e) {
            throw new FilesystemException('Filesystem: changeGroup Exception ' . $e->getMessage());
        }
    }

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
    public function changePermission($path, $permission, $recursive = false)
    {
        try {

            return $this->fsType->changePermission($path, $permission, $recursive);

        } catch (Exception $e) {
            throw new FilesystemException('Filesystem: changePermission Exception ' . $e->getMessage());
        }
    }

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
    public function touch($path, $modification_time = null, $access_time = null, $recursive = false)
    {
        try {

            return $this->fsType->touch($path, $modification_time, $access_time, $recursive);

        } catch (Exception $e) {
            throw new FilesystemException('Filesystem: Touch Exception ' . $e->getMessage());
        }
    }

    /**
     * Close the Connection
     *
     * @return  $this
     * @since   1.0
     * @throws  FilesystemException
     * @api
     */
    public function close()
    {
        $this->fsType->close();

        return $this;
    }
}
