<?php
/**
 * Adapter for Filesystem
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Filesystem;

use Exception;
use Exception\Filesystem\RuntimeException;
use CommonApi\Filesystem\FilesystemInterface;

/**
 * Adapter for Filesystem
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0
 */
class Adapter
{
    /**
     * Filesystem Handler
     *
     * @var     object
     * @since   1.0
     */
    public $handler;

    /**
     * Constructor
     *
     * @param   FilesystemInterface $filesystem
     *
     * @since   1.0
     */
    public function __construct(FilesystemInterface $filesystem)
    {
        $this->handler = $filesystem;
    }

    /**
     * Determines if file or folder identified in path exists
     *
     * @param   string $path
     *
     * @return  bool
     * @since   1.0
     * @throws  RuntimeException
     */
    public function exists($path)
    {
        try {
            return $this->handler->exists($path);
        } catch (Exception $e) {
            throw new RuntimeException('Filesystem: Exists Exception ' . $e->getMessage());
        }
    }

    /**
     * Returns an associative array of metadata for the file or folder specified in path
     *
     * @param   string $path
     *
     * @return  object
     * @since   1.0
     * @throws  RuntimeException
     */
    public function getMetadata($path)
    {
        try {
            return $this->handler->getMetadata($path);
        } catch (Exception $e) {
            throw new RuntimeException('Filesystem: getMetadata Exception ' . $e->getMessage());
        }
    }

    /**
     * Returns the contents of the file identified in path
     *
     * @param   string $path
     *
     * @return  null|string
     * @since   1.0
     * @throws  RuntimeException
     */
    public function read($path)
    {
        try {
            return $this->handler->read($path);
        } catch (Exception $e) {
            throw new RuntimeException('Filesystem: Read Exception ' . $e->getMessage());
        }
    }

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
     * @throws  RuntimeException
     */
    public function getList(
        $path,
        $recursive = false,
        $extension_list = '',
        $include_files = false,
        $include_folders = false,
        $filename_mask = null
    ) {
        try {
            return $this->handler->getList(
                $path,
                $recursive,
                $extension_list,
                $include_files,
                $include_folders,
                $filename_mask
            );
        } catch (Exception $e) {
            throw new RuntimeException('Filesystem: getList Exception ' . $e->getMessage());
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
     * @throws  RuntimeException
     */
    public function write($path, $data = '', $replace = true, $append = false, $truncate = false)
    {
        try {
            return $this->handler->write($path, $data, $replace, $append, $truncate);
        } catch (Exception $e) {
            throw new RuntimeException('Filesystem: Write Exception ' . $e->getMessage());
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
     * @throws  RuntimeException
     */
    public function delete($path, $recursive = false)
    {
        try {
            return $this->handler->delete($path, $recursive);
        } catch (Exception $e) {
            throw new RuntimeException('Filesystem: Delete Exception ' . $e->getMessage());
        }
    }

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
     * @throws  RuntimeException
     */
    public function copy(
        $path,
        $target_directory,
        $target_name = '',
        $replace = true,
        $target_handler = ''
    ) {
        try {
            return $this->handler->copy(
                $path,
                $target_directory,
                $target_name,
                $replace,
                $target_handler
            );
        } catch (Exception $e) {
            throw new RuntimeException('Filesystem: Copy Exception ' . $e->getMessage());
        }
    }

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
     * @throws  RuntimeException
     */
    public function move(
        $path,
        $target_directory,
        $target_name = '',
        $replace = true,
        $target_handler = ''
    ) {
        try {
            return $this->handler->move(
                $path,
                $target_directory,
                $target_name,
                $replace,
                $target_handler
            );
        } catch (Exception $e) {
            throw new RuntimeException('Filesystem: Move Exception ' . $e->getMessage());
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
     * @throws  RuntimeException
     */
    public function rename($path, $new_name)
    {
        try {
            return $this->handler->rename($path, $new_name);
        } catch (Exception $e) {
            throw new RuntimeException('Filesystem: Rename Exception ' . $e->getMessage());
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
     * @throws  RuntimeException
     */
    public function changeOwner($path, $user_name, $recursive = false)
    {
        try {
            return $this->handler->changeOwner($path, $user_name, $recursive);
        } catch (Exception $e) {
            throw new RuntimeException('Filesystem: changeOwner Exception ' . $e->getMessage());
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
     * @throws  RuntimeException
     */
    public function changeGroup($path, $group_id, $recursive = false)
    {
        try {
            return $this->handler->changeGroup($path, $group_id, $recursive);
        } catch (Exception $e) {
            throw new RuntimeException('Filesystem: changeGroup Exception ' . $e->getMessage());
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
     * @throws  RuntimeException
     */
    public function changePermission($path, $permission, $recursive = false)
    {
        try {
            return $this->handler->changePermission($path, $permission, $recursive);
        } catch (Exception $e) {
            throw new RuntimeException('Filesystem: changePermission Exception ' . $e->getMessage());
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
     * @throws  RuntimeException
     */
    public function touch($path, $modification_time = null, $access_time = null, $recursive = false)
    {
        try {
            return $this->handler->touch($path, $modification_time, $access_time, $recursive);
        } catch (Exception $e) {
            throw new RuntimeException('Filesystem: Touch Exception ' . $e->getMessage());
        }
    }
}
