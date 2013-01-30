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
    public function connect($filesystem_type_object)
    {
        $this->filesystem_type_object = $filesystem_type_object;

        $this->filesystem_type_object = $this->filesystem_type_object->connect();

        $this->connection = true;

        return $this->filesystem_type_object;
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
        $this->path = $this->filesystem_type_object->setPath($path);

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
        /** System Interface */
        $this->filesystem_type       = $this->getFilesystemType();
        $this->root                  = $this->getRoot();
        $this->persistence           = $this->getPersistence();
        $this->directory_permissions = $this->getDirectoryPermissions();
        $this->file_permissions      = $this->getFilePermissions();
        $this->read_only             = $this->getReadOnly();
        $this->owner                 = $this->getOwner();
        $this->group                 = $this->getGroup();
        $this->create_date           = $this->getCreateDate();
        $this->access_date           = $this->getAccessDate();
        $this->modified_date         = $this->getModifiedDate();
        $this->is_readable           = $this->isReadable();
        $this->is_writable           = $this->isWriteable();
        $this->is_executable         = $this->isExecutable();

        /** Path Interface */
        $this->path   = $this->getPath();
        $this->exists = $this->exists();

        if ($this->exists === true) {
            $this->absolute_path = $this->getAbsolutePath();
            $this->is_absolute   = $this->isAbsolute();
            $this->is_directory  = $this->isDirectory();
            $this->is_file       = $this->isFile();
            $this->is_link       = $this->isLink();
            $this->type          = $this->getType();
            $this->name          = $this->getName();
            $this->parent        = $this->getParent();
            $this->extension     = $this->getExtension();
            $this->size          = $this->getSize();
            $this->mime_type     = $this->getMimeType();
        }

        return $this->filesystem_type_object;
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
        $data = $this->filesystem_type_object->read();

        return $data;
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
        $results = $this->filesystem_type_object->write($file, true, $data);

        return $results;
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
        $results = $this->filesystem_type_object->delete($delete_subdirectories);

        return $results;
    }

    /**
     * Moves the file/folder in $this->path to the target_directory, replacing content, if indicated
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
    public function move($target_directory, $replace = true, $target_filesystem_type = '')
    {
        $this->copy($target_directory, $replace, $target_filesystem_type);

        $this->delete(true);

        return;
    }

    /**
     * Copies the file/folder in $path to the target_directory, replacing content, if indicated
     *
     * Note: $target_filesystem_type used to create new filesystem instance for target
     *
     * @param   string  $target_directory
     * @param   bool    $replace                 defaults to true
     * @param   string  $target_filesystem_type  defaults to current
     *
     * @return  bool
     * @since   1.0
     * @throws  FileException
     */
    public function copy($target_directory, $replace = true, $target_filesystem_type = '')
    {
        $data = $this->filesystem_type_object->read();

        $results = $target_filesystem_type->write($target_directory, basename($this->path), $data, $replace);

        return $results;
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
        $data = $this->filesystem_type_object->getList($recursive);

        return $data;
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
        $results = $this->filesystem_type_object->chmod($mode);

        return $results;
    }

    /**
     * Update the touch time and/or the access time for the directory or file identified in the path
     *
     * @param   null    $time
     * @param   null    $atime
     *
     * @return  void
     * @throws  FileException
     * @since   1.0
     */
    public function touch($time = null, $atime = null)
    {
        $results = $this->filesystem_type_object->touch($time, $atime);

        return bool;
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
}
