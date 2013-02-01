<?php
/**
 * Filesystem Adapter
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem\Targetinterface;

defined('MOLAJO') or die;

use Molajo\Filesystem\Exception\FileException;
use Molajo\Filesystem\Exception\FileNotFoundException;

/**
 * Filesystem Target Interface for Filesystem Adapter
 *
 * @package    Molajo
 * @license    MIT
 * @copyright  2013 Amy Stephen. All rights reserved.
 * @since      1.0
 */
class Filesystem extends Path implements FileInterface
{
    /**
     * Constructor
     *
     * @since   1.0
     */
    public function __construct()
    {
        return parent::__construct();
    }

    /**
     * Connect to the Filesystem
     *
     * @param   object  Filesystem Type
     *
     * @return  bool
     * @since   1.0
     */
    public function connect($fs_type)
    {
        $this->fs_type = $fs_type;

        $this->fs_type = $this->fs_type->connect();

        $this->connection = true;

        return $this->fs_type;
    }

    /**
     * Set the Path
     *
     * @param   string  $path
     *
     * @return  string
     * @since   1.0
     */
    public function setPath($path)
    {
        $this->path = $this->fs_type->setPath($path);

        return $this->path;
    }

    /**
     * Retrieves and sets metadata for the file specified in path
     *
     * @return  object  Filesystem
     * @since   1.0
     */
    public function getMetadata()
    {
        $this->filesystem_type               = $this->getFilesystemType();
        $this->root                          = $this->getRoot();
        $this->persistence                   = $this->getPersistence();
        $this->default_directory_permissions = $this->getDirectoryPermissions();
        $this->default_file_permissions      = $this->getFilePermissions();
        $this->read_only                     = $this->getReadOnly();
        $this->path                          = $this->getPath();
        $this->is_absolute_path              = $this->isAbsolutePath();
        $this->absolute_path                 = $this->getAbsolutePath();
//$this->relative_path                 = $this->getRelativePath();
        $this->exists                        = $this->exists();
        $this->name                          = $this->getName();

        $this->is_directory                  = $this->isDirectory();
        $this->is_file                       = $this->isFile();
        $this->is_link                       = $this->isLink();
        $this->type                          = $this->getType();

        $this->parent                        = $this->getParent();
        $this->extension                     = $this->getExtension();
        $this->size                          = $this->getSize();
        $this->mime_type                     = $this->getMimeType();
        $this->owner                         = $this->getOwner();
        $this->group                         = $this->getGroup();
        $this->create_date                   = $this->getCreateDate();
        $this->access_date                   = $this->getAccessDate();
        $this->modified_date                 = $this->getModifiedDate();
        $this->is_readable                   = $this->isReadable();
        $this->is_writable                   = $this->isWriteable();
        $this->is_executable                 = $this->isExecutable();


        return $this->fs_type;
    }

    /**
     * Returns the contents of the file identified by path
     *
     * @return  mixed|string|array
     * @since   1.0
     * @throws  FileNotFoundException
     */
    public function read()
    {
        $this->action_results = $this->fs_type->read();

        return $this->action_results;
    }

    /**
     * Creates or replaces the file or directory identified in path using the data value
     *
     * @param   string  $file     default '' (create directory)
     * @param   bool    $replace  default true
     * @param   string  $data     default ''
     *
     * @return  bool
     * @since   1.0
     * @throws  FileException
     */
    function write($file = '', $replace = true, $data = '')
    {
        $this->action_results = $this->fs_type->write($file, $replace, $data);

        return $this->action_results;
    }

    /**
     * Deletes the file or folder identified in path. Empty directories are removed, if so indicated
     *
     * @param   bool    $delete_subdirectories
     *
     * @return  null
     * @since   1.0
     * @throws  FileException
     */
    public function delete($delete_subdirectories = true)
    {
        $this->action_results = $this->fs_type->delete($delete_subdirectories);

        return $this->action_results;
    }

    /**
     * Copies the file/folder in $path to the target_directory, replacing content, if indicated
     *
     * Note: $target_filesystem_type used to create new filesystem instance for target
     *
     * @param   string  $target_directory
     * @param   bool    $target_name
     * @param   bool    $replace                  defaults to true
     * @param   string  $target_filesystem_type   defaults to current
     *
     * @return  bool
     * @since   1.0
     */
    public function copy($target_directory, $target_name, $replace = true, $target_filesystem_type = '')
    {
        $this->action_results
            = $this->fs_type
            ->copy($target_directory, $target_name, $replace, $target_filesystem_type);

        return $this->action_results;
    }

    /**
     * Moves the file/folder in $this->path to the target_directory, replacing content, if indicated
     *
     * Note: $target_filesystem_type used to create new filesystem instance for target
     *
     * @param   string  $target_directory
     * @param   bool    $target_name
     * @param   bool    $replace                  defaults to true
     * @param   string  $target_filesystem_type   defaults to current
     *
     * @return  bool
     * @since   1.0
     */
    public function move($target_directory, $target_name, $replace = true, $target_filesystem_type = '')
    {
        $this->action_results
            = $this->fs_type
            ->move($target_directory, $target_name, $replace, $target_filesystem_type);

        return $this->action_results;
    }

    /**
     * Returns a list of file and folder names located at path directory
     *
     * @param   bool  $recursive
     *
     * @return  array|string
     * @since   1.0
     */
    public function getList($recursive = false)
    {
        $this->action_results = $this->fs_type->getList($recursive);

        return $this->action_results;
    }

    /**
     * Change the file mode for 'owner', 'group', and 'world', and read, write, execute access
     *
     * Mode: R/W for owner, nothing for everyone else '0600'
     *  R/W for owner, read for everyone else '0644'
     *  Everything for owner, R/E for others - '0755'
     *  Everything for owner, read and execute for group - '0750'
     *
     * Notes: The current user is the user under which PHP runs. It is probably not the same
     *  user you use for normal shell or FTP access. The mode can be changed only by user
     *  who owns the file on most systems.
     *
     * @param   string  $mode
     *
     * @return  string|void
     * @since   1.0
     * @throws  FileException
     */
    public function chmod($mode = '')
    {
        $this->action_results = $this->fs_type->chmod($mode);

        return $this->action_results;
    }

    /**
     * Update the touch time and/or the access time for the directory or file identified in the path
     *
     * @param   null    $time
     * @param   null    $atime
     *
     * @return  bool
     * @throws  FileException
     * @since   1.0
     */
    public function touch($time = null, $atime = null)
    {
        $this->action_results = $this->fs_type->touch($time, $atime);

        return $this->action_results;
    }

    /**
     * Close the Local Connection
     *
     * @return  null
     * @since   1.0
     */
    public function close()
    {
        return;
    }

    /**
     * Utility method - force consistency in True and False
     *
     * @param   bool  $variable
     * @param   bool  $default
     *
     * @return  bool
     * @since   1.0
     */
    public function forceTrueOrFalse($variable, $default = false)
    {
        if ($default === true) {

            if ($variable === false) {
            } else {
                $variable = true;
            }

        } else {
            if ($variable === true) {
            } else {
                $variable = false;
            }
        }

        return $default;
    }
}
