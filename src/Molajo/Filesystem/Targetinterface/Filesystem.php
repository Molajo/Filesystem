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
     * @param   array  $options
     *
     * @since   1.0
     */
    public function __construct($filesystem_type)
    {
        parent::__construct($filesystem_type);

        return $this;
    }

    /**
     * Connect to the Filesystem
     *
     * @return  bool
     * @since   1.0
     */
    public function connect()
    {
        $this->filesystem_type = $this->filesystem_type->connect();

        return $this;
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
        $this->path = $this->filesystem_type->setPath($path);

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
        $this->filesystem_type = $this->getFilesystemType();
        $this->root            = $this->getRoot();
        $this->persistence     = $this->getPersistence();
        $this->owner           = $this->getOwner();
        $this->group           = $this->getGroup();
        $this->create_date     = $this->getCreateDate();
        $this->access_date     = $this->getAccessDate();
        $this->modified_date   = $this->getModifiedDate();
        $this->is_readable     = $this->isReadable();
        $this->is_writeable    = $this->isWriteable();
        $this->is_executable   = $this->isExecutable();

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

        return $this;
    }

    /**
     * Returns the contents of the file identified by path
     *
     * @return  mixed|string|array
     * @since   1.0
     * @throws  AdapterNotFoundException when file does not exist
     */
    public function read()
    {
        if ($path == '') {
            $path = $this->path;
        }

        $path = $this->filesystem_type->normalise($path);

        if ($this->exists($path) === false) {
            throw new FileNotFoundException ('Filesystem: could not find file at path: ', $path);
        }

        if ($this->isFile($path)) {
        } else {
            throw new FileNotFoundException ('Filesystem: not a valid file path: ', $path);
        }

        $data = false;

        try {
            $data = file_get_contents($path);

        } catch (\Exception $e) {

            throw new FileNotFoundException
            ('Filesystem: error reading path ' . $path);
        }

        if ($data === false) {
            throw new FileNotFoundException ('Filesystem: could not read: ', $path);
        }

        return $data;
    }

    /**
     * Creates or replaces the file or directory identified in path using the data value
     *
     * @param   string  $name
     * @param   bool    $replace
     * @param   string  $data
     *
     * @return  object  Filesystem
     * @since   1.0
     * @throws  FileException
     */
    function write($name, $replace, $data = '')
    {

        if (trim($data) == '' || strlen($data) == 0) {
            throw new FileException
            ('Filesystem: attempting to write no data to file: ' . $this->path . '/' . $name);
        }

        //isdirectory or isfile or die
        //\mk_dir($path, $this->directory_permissions, true);

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
     * @param   string  $path
     * @param   bool    $delete_empty
     *
     * @return  null
     * @since   1.0
     */
    public function delete($path = '', $delete_empty = true)
    {
        if ($path == '') {
            $path = $this->path;
        }

        $path = $this->filesystem_type->normalise($path);
        //rmdir($this->computePath($key));
        if (file_exists($path)) {

            if ($this->isWriteable($path) === false) {
                throw new FileException
                ('Filesystem: file to be deleted is not writable: ' . $path);
            }

            $handle = fopen($path, 'r+');

            if (flock($handle, LOCK_EX)) {
            } else {
                throw new FileException
                ('Filesystem: Lock not obtainable for delete for: ' . $path);
            }

            fclose($handle);
        }

        try {
            \unlink($path);

        } catch (Exception $e) {

            throw new FileException
            ('Filesystem: Delete failed for: ' . $path);
        }

        return true;
    }

    /**
     * Copies the file identified in $path to the target_adapter in the new_parent_directory,
     *  replacing existing contents, if indicated, and creating directories needed, if indicated
     *
     * Note: $target_filesystem_type is an instance of the Filesystem exclusive to the target portion of the copy
     *
     * @param   string  $path
     * @param   string  $target_filesystem_type
     * @param   string  $target_directory
     * @param   bool    $replace
     *
     * @return  null|void
     * @since   1.0
     * @throws  FileException
     */
    public function copy($path = '', $target_filesystem_type, $target_directory, $replace = false)
    {
        if ($path == '') {
            $path = $this->path;
        }

        $data = $this->read($path);

        $results = $target_filesystem_type->write($target_directory, basename($path), $data, $replace);

        if ($results === false) {
            throw new FileException('Could not write the "%s" key content.',
                $target_directory . '\' . $file_name');
        }

        return;
    }

    /**
     * Moves the file identified in path to the location identified in the new_parent_directory
     *  replacing existing contents, if indicated, and creating directories needed, if indicated
     *
     * @param   string  $path
     * @param   File    $target
     * @param   string  $target_directory
     * @param   bool    $replace
     *
     * @return  null
     * @since   1.0
     */
    public function move($path = '', $target_filesystem_type, $target_directory, $replace = false)
    {
        if ($path == '') {
            $path = $this->path;
        }

        $data = $this->read($path);

        $target_filesystem_type->write($target_directory, $data, $replace);

        $this->delete($path);

        return;
    }

    /**
     * Returns the contents of the directory located at path directory
     *
     * @param   bool  $recursive
     *
     * @return  mixed;
     * @since   1.0
     */
    public function getList($recursive = false)
    {

        if (file_exists($path)) {
            return file_get_contents($path);
        }

        $iterator = $this->pathname->rootAdapter()->getIterator($this->pathname, func_get_args());

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
     * @param   int     $mode
     *
     * @return  int|null
     * @throws  FileException
     * @since   1.0
     */
    public function chmod($mode)
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
     * @param   int     $time
     * @param   int     $atime
     *
     * @return  int|null
     * @throws  FileException
     * @since   1.0
     */
    public function touch($time, $atime = null)
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

        return $time;
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
