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

use \DateTime;
use \Exception;
use \RuntimeException;

use Molajo\Filesystem\Exception\AccessDeniedException as AccessDeniedException;
use Molajo\Filesystem\Exception\AdapterNotFoundException as AdapterNotFoundException;
use Molajo\Filesystem\Exception\FileException as FileException;
use Molajo\Filesystem\Exception\FileExceptionInterface as FileExceptionInterface;
use Molajo\Filesystem\Exception\FileNotFoundException as FileNotFoundException;
use Molajo\Filesystem\Exception\InvalidPathException as InvalidPathException;

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

    /**
     * @return array|mixed|string
     */
    public function read()
    {
        if ($this->exists($this->path) === false) {
            throw new FileNotFoundException ('Filesystem: could not find file at path: ', $this->path);
        }

        if ($this->isFile($this->path)) {
        } else {
            throw new FileNotFoundException ('Filesystem: not a valid file path: ', $this->path);
        }

        try {
            $data = file_get_contents($this->path);

        } catch (\Exception $e) {

            throw new FileNotFoundException
            ('Filesystem: error reading path ' . $this->path);
        }

        if ($data === false) {
            throw new FileNotFoundException ('Filesystem: could not read: ', $this->path);
        }

        return $data;
    }

    /**
     * Creates or replaces the file or directory identified in path using the data value
     *
     * @param   string  $file
     * @param   bool    $replace  default true
     * @param   string  $data     default ''
     *
     * @return  object  Filesystem
     * @since   1.0
     * @throws  FileException
     */
    function write($file, $replace = true, $data = '')
    {

        if (trim($data) == '' || strlen($data) == 0) {
            throw new FileException
            ('Filesystem: attempting to write no data to file: ' . $this->path . '/' . $name);
        }

        //is_directory or is_file or die
        //\mk_dir($this->path, $this->directory_permissions, true);

        if (file_exists($this->path . '/' . $name)) {

            if ($replace === false) {
                throw new FileException
                ('Filesystem: attempting to write to existing file: ' . $this->path . '/' . $name);
            }

            if ($this->isWriteable($this->path . '/' . $name) === false) {
                throw new FileException
                ('Filesystem: file is not writable: ' . $this->path . '/' . $name);
            }

            $handle = fopen($this->path . '/' . $name, 'r+');

            if (flock($handle, LOCK_EX)) {
            } else {
                throw new FileException
                ('Filesystem: Lock not obtainable for file write for: ' . $this->path . '/' . $name);
            }

            fclose($handle);
        }

        try {
            \file_put_contents($this->path . '/' . $name, $data, LOCK_EX);

        } catch (Exception $e) {
            throw new FileException
            ('Directories do not exist for requested file: .' . $this->path . '/' . $name);
        }

        return true;
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

        //rmdir($this->computePath($key));
        if (file_exists($this->path)) {

            if ($this->isWriteable($this->path) === false) {
                throw new FileException
                ('Filesystem: file to be deleted is not writable: ' . $this->path);
            }

            $handle = fopen($this->path, 'r+');

            if (flock($handle, LOCK_EX)) {
            } else {
                throw new FileException
                ('Filesystem: Lock not obtainable for delete for: ' . $this->path);
            }

            fclose($handle);
        }

        try {
            \unlink($this->path);

        } catch (Exception $e) {

            throw new FileException
            ('Filesystem: Delete failed for: ' . $this->path);
        }

        return true;
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

        $this->delete();

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
     * @return  void
     * @since   1.0
     * @throws  FileException
     */
    public function copy($target_directory, $replace = true, $target_filesystem_type = '')
    {
        $data = $this->read($this->path);

        $results = $target_filesystem_type->write($target_directory, basename($this->path), $data, $replace);

        if ($results === false) {
            throw new FileException('Could not write the "%s" key content.',
                $target_directory . '\' . $file_name');
        }

        return;
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

        if (file_exists($path)) {
            return file_get_contents($path);
        }

        //$iterator = $this->path->rootAdapter()->getIterator($this->pathname, func_get_args());

// cheap array creation
        if (method_exists($iterator, 'toArray')) {
            return $iterator->toArray();
        }

        $files = array();
        foreach ($iterator as $file) {
            $files[] = $file;
        }

        return $files;
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
        if (file_exists($this->path)) {
        } else {

            throw new FileException
            ('Filesystem: chmod method. File does not exist' . $this->path);
        }

        if (in_array($mode, array('0600', '0644', '0755', '0750'))) {
        } else {

            throw new FileException
            ('Filesystem: chmod method. Mode not provided: ' . $mode);
        }

        try {
            chmod($this->path, $mode);

        } catch (Exception $e) {

            throw new FileException
            ('Filesystem: chmod method failed for path ' . $this->path . ' mode: ' . $mode);
        }

        return $mode;
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
        if (file_exists($this->path)) {
        } else {

            throw new FileException
            ('Filesystem: setModifiedDate method. File does not exist' . $this->path);
        }

        if ($time == '' || $time === null || $time == 0) {
            $time = time();
        }

        try {

            if (touch($this->path, $time)) {
                echo $this->path . ' modification time has been changed to present time';

            } else {
                echo 'Sorry, could not change modification time of ' . $this->path;
            }

        } catch (Exception $e) {

            throw new FileException
            ('Filesystem: is_readable method failed for ' . $this->path);
        }

        return;
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
