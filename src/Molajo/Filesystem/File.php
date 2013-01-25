<?php
/**
 * File Class for Filesystem
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem;

defined ('MOLAJO') or die;


use Molajo\Filesystem\FileInterface;
use Molajo\Filesystem\PathInterface;
use Molajo\Filesystem\SystemInterface;

use \Exception;
use \RuntimeException;
use Molajo\Filesystem\Exception\AccessDeniedException as AccessDeniedException;
use Molajo\Filesystem\Exception\AdapterNotFoundException as AdapterNotFoundException;
use Molajo\Filesystem\Exception\FileException as FileException;
use Molajo\Filesystem\Exception\FileExceptionInterface as FileExceptionInterface;
use Molajo\Filesystem\Exception\FileNotFoundException as FileNotFoundException;
use Molajo\Filesystem\Exception\InvalidPathException as InvalidPathException;

/**
 * File Class for Filesystem
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class File extends Path implements FileInterface
{

    /**
     * Constructor
     *
     * @param   array  $options
     *
     * @since   1.0
     */
    public function __construct ($path, $options = array())
    {
        parents::__construct ($path, $options);

        return;
    }

    /**
     * Returns the contents of the file identified by path
     *
     * @param   string  $path
     *
     * @return  mixed
     * @since   1.0
     * @throws  AdapterNotFoundException when file does not exist
     */
    public function read ($path = '')
    {
        $path = $this->normalise ($path);

        if ($this->exists ($path) === false) {
            throw new FileNotFoundException ('Filesystem: could not find file at path: ', $path);
        }

        if ($this->isFile ($path)) {
        } else {
            throw new FileNotFoundException ('Filesystem: not a valid file path: ', $path);
        }

        $data = false;

        try {
            $data = file_get_contents ($path);

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
     * Creates or replaces the file identified in path using the data value
     *
     * @param   string  $path
     * @param   string  $file
     * @param   string  $data
     * @param   bool    $replace
     *
     * @return  null
     * @since   1.0
     * @throws  FileException
     */
    function write ($path, $file, $data, $replace)
    {
        $path = $this->normalise ($path);

        if (trim ($data) == '' || strlen ($data) == 0) {
            throw new FileException
            ('Filesystem: attempting to write no data to file: ' . $path . '/' . $file);
        }

        if (file_exists ($path . '/' . $file)) {

            if ($replace === false) {
                throw new FileException
                ('Filesystem: attempting to write to existing file: ' . $path . '/' . $file);
            }

            if ($this->isWriteable ($path . '/' . $file) === false) {
                throw new FileException
                ('Filesystem: file is not writable: ' . $path . '/' . $file);
            }

            $handle = fopen ($path . '/' . $file, 'r+');

            if (flock ($handle, LOCK_EX)) {
            } else {
                throw new FileException
                ('Filesystem: Lock not obtainable for file write for: ' . $path . '/' . $file);
            }

            fclose ($handle);
        }

        try {
            \file_put_contents ($path . '/' . $file, $data, LOCK_EX);

        } catch (Exception $e) {
            throw new FileException
            ('Directories do not exist for requested file: .' . $path . '/' . $file);
        }

        return true;
    }

    /**
     * Deletes the file identified in path. Empty directories are removed if so indicated
     *
     * @param   string  $path
     * @param   bool    $delete_empty_directory
     *
     * @return  null
     * @since   1.0
     */
    public function delete ($path, $delete_empty_directory = true)
    {
        $path = $this->normalise ($path);

        if (file_exists ($path)) {

            if ($this->isWriteable ($path) === false) {
                throw new FileException
                ('Filesystem: file to be deleted is not writable: ' . $path);
            }

            $handle = fopen ($path, 'r+');

            if (flock ($handle, LOCK_EX)) {
            } else {
                throw new FileException
                ('Filesystem: Lock not obtainable for delete for: ' . $path);
            }

            fclose ($handle);
        }

        try {
            \unlink ($path);

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
     * Note: $target_filesystem is an instance of the Filesystem exclusive to the target portion of the copy
     *
     * @param   string  $path
     * @param   string  $target_filesystem
     * @param   string  $target_directory
     * @param   bool    $replace
     *
     * @return  null|void
     * @since   1.0
     * @throws  FileException
     */
    public function copy ($path, $target_filesystem, $target_directory, $replace = false)
    {
        $options                 = array();
        $options['adapter_name'] = $target_filesystem;
        $class                   = 'Molajo\\Filesystem\\Adapter';
        $target                  = new $class($target_directory, $options);

        $data = $this->read ($path);

        $results = $target->write ($target_directory, basename ($path), $data, $replace);

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
    public function move ($path, $target_filesystem, $target_directory, $replace = false)
    {
        $data = $this->read ($path);

        $target->write ($target_directory, $data, $replace);

        $this->delete ($path);

        return;
    }

    /**
     * Returns the contents of the file located at path directory
     *
     * @param   string  $path
     *
     * @return  mixed;
     * @since   1.0
     */
    public function getList ($path)
    {
        $path = $this->normalise ($path);

        if (file_exists ($path)) {
            return file_get_contents ($path);
        }

        $iterator = $this->pathname->rootAdapter ()->getIterator ($this->pathname, func_get_args ());

// cheap array creation
        if (method_exists ($iterator, 'toArray')) {
            return $iterator->toArray ();
        }

        $files = array();
        foreach ($iterator as $file) {
            $files[] = $file;
        }

        return $files;

        return false;
    }

    /**
     * Creates directory identified in path using the data value
     *
     * @param   string  $path
     * @param   string  $new_name
     * @param   bool    $replace
     *
     * @return  null
     * @since   1.0
     */
    public function createDirectory ($path, $new_name, $replace = false)
    {
        $path = $this->normalise ($path);

        if ($replace === false) {
            if (file_exists ($path)) {
                return false;
            }
        }

        if (file_exists ($path)) {
            return file_get_contents ($path);
        }

        \mk_dir ($path, 0755, true);

        // Desired folder structure
        $structure = './depth1/depth2/depth3/';

// To create the nested structure, the $recursive parameter
// to mkdir() must be specified.

        if (! mkdir ($structure, 0, true)) {
            die('Failed to create folders...');
        }


        return false;
    }

    /**
     * Delete directory identified in path using the data value
     *
     * @param   string  $path
     * @param   bool    $create_subdirectories
     *
     * @return  null
     * @since   1.0
     */
    public function deleteDirectory ($path, $delete_subdirectories = true)
    {
        $path = $this->normalise ($path);


        if (file_exists ($path)) {
            return file_get_contents ($path);
        }

        \mk_dir ($path);

        if ($this->isDirectory ($key)) {
            return rmdir ($this->computePath ($key));
        }

        // Desired folder structure
        $structure = './depth1/depth2/depth3/';

// To create the nested structure, the $recursive parameter
// to mkdir() must be specified.

        if (! mkdir ($structure, 0, true)) {
            die('Failed to create folders...');
        }


        return false;
    }

    /**
     * Retrieves metadata for the file specified in path and returns an associative array
     *  minimally populated with: last_accessed_date, last_updated_date, size, mimetype,
     *  absolute_path, relative_path, filename, and file_extension.
     *
     * @param   string  $path
     * @param   string  $options
     *
     * @return  null
     * @since   1.0
     */
    public function getMetadata ($path, $options)
    {

    }

    /**
     * Normalizes the given path
     *
     * @param   $path
     *
     * @return  string
     * @since   1.0
     */
    public function normalise ($path = '')
    {
        if ($path == '') {
            $path = $this->path;
        }

        $absolute_path = false;
        if (substr ($path, 0, 1) == '/') {
            $absolute_path = true;
            $path          = substr ($path, 1, strlen ($path));
        }

        /** Unescape slashes */
        $path = str_replace ('\\', '/', $path);

        /**  Filter: empty value
         *
         * @link http://tinyurl.com/arrayFilterStrlen
         */
        $nodes = array_filter (explode ('/', $path), 'strlen');

        $normalised = array();

        foreach ($nodes as $node) {

            /** '.' means current - ignore it      */
            if ($node == '.') {

                /** '..' is parent - remove the parent */
            } elseif ($node == '..') {

                if (count ($normalised) > 0) {
                    array_pop ($normalised);
                }

            } else {
                $normalised[] = $node;
            }

        }

        $path = implode ('/', $normalised);
        if ($absolute_path === true) {
            $path = '/' . $path;
        }

        if (0 !== strpos ($path, $this->directory)) {
            throw new \OutOfBoundsException(sprintf ('The path "%s" is out of the filesystem.', $path));
        }

        return $path;
    }


    /**
     * SystemInterface
     */

    /**
     * Method to connect to a Local server
     *
     * @return  object|resource
     * @since   1.0
     */
    public function connect ()
    {
        return;
    }

    /**
     * Get the Connection
     *
     * @return  null
     * @since   1.0
     */
    function getConnection ()
    {
        return;
    }

    /**
     * Set the Connection
     *
     * @return  null
     * @since   1.0
     */
    function setConnection ()
    {
        return;
    }

    /**
     * Close the Local Connection
     *
     * @return  null
     * @since   1.0
     */
    public function close ()
    {
        return;
    }

    /**
     * Returns the owner of the file or directory defined in the path
     *
     * @param   string  $path
     *
     * @return  bool
     * @since   1.0
     */
    public function getOwner ($path)
    {
        return fileowner ($path);
    }

    /**
     * Returns the group for the file or directory defined in the path
     *
     * @param   string  $path
     *
     * @return  string
     * @since   1.0
     */
    public function getGroup ($path)
    {
        return filegroup ($path);
    }

    /**
     * Retrieves Create Date for directory or file identified in the path
     *
     * @param   string  $path
     *
     * @return  null
     * @since   1.0
     */
    public function getCreateDate ($path)
    {
        if ($path == '') {
            $path = $this->path;
        }

        $path = $this->normalise ($path);

        if (file_exists ($path)) {
        } else {
            throw new FileException
            ('Filesystem: getCreateDate method. File does not exist' . $path);
        }

        try {
            echo \date ("F d Y H:i:s.", filectime ($path));

        } catch (Exception $e) {
            throw new FileException

            ('Filesystem: getCreateDate method failed for ' . $path);
        }

        return;
    }

    /**
     * Retrieves Last Access Date for directory or file identified in the path
     *
     * @param   string  $path
     *
     * @return  null
     * @since   1.0
     */
    public function getAccessDate ($path)
    {
        if ($path == '') {
            $path = $this->path;
        }

        $path = $this->normalise ($path);


        if (file_exists ($path)) {
        } else {
            throw new FileException
            ('Filesystem: getAccessDate method. File does not exist' . $path);
        }

        try {
            echo \date ("F d Y H:i:s.", fileatime ($path));

        } catch (Exception $e) {
            throw new FileException

            ('Filesystem: getAccessDate method failed for ' . $path);
        }

        return;
    }

    /**
     * Retrieves Last Update Date for directory or file identified in the path
     *
     * @param   string  $path
     *
     * @return  null
     * @since   1.0
     */
    public function getModifiedDate ($path)
    {
        if ($path == '') {
            $path = $this->path;
        }

        $path = $this->normalise ($path);

        if (file_exists ($path)) {
        } else {
            throw new FileException
            ('Filesystem: getModifiedDate method. File does not exist' . $path);
        }

        try {
            echo \date ("F d Y H:i:s.", filemtime ($path));

        } catch (Exception $e) {
            throw new FileException

            ('Filesystem: getModifiedDate method failed for ' . $path);
        }

        return;
    }

    /**
     * Tests if the group specified: 'owner', 'group', or 'world' has read access
     *  Returns true or false
     *
     * @param   string  $path
     * @param   string  $group
     *
     * @return  null
     * @since   1.0
     */
    public function isReadable ($path)
    {
        return is_readable ($path);
    }

    /**
     * Tests if the group specified: 'owner', 'group', or 'world' has write access
     *  Returns true or false
     *
     * @param   string  $path
     * @param   string  $group
     *
     * @return  null
     * @since   1.0
     */
    public function isWriteable ($path)
    {
        return is_writable ($path);
    }

    /**
     * Tests if the group specified: 'owner', 'group', or 'world' has execute access
     *  Returns true or false
     *
     * @param   string  $path
     * @param   string  $group
     *
     * @return  null
     * @since   1.0
     */
    public function isExecutable ($path)
    {
        return is_executable ($path);
    }

    /**
     * Change the file mode for 'owner', 'group', and 'world', and read, write, execute access
     *
     * Mode: R/W for owner, nothing for everyone else '0600'
     *  R/W for owner, read for everyone else '0644'
     *  Everything for woner, R/E for others - '0755'
     *  Everything for owner, read and execute for group - '0750'
     *
     * Notes: The current user is the user under which PHP runs. It is probably not the same
     *  user you use for normal shell or FTP access. The mode can be changed only by user
     *  who owns the file on most systems.
     *
     * @param   string  $path
     * @param   int     $mode
     *
     * @return  null
     * @since   1.0
     */
    public function chmod ($path, $mode)
    {
        if ($path == '') {
            $path = $this->path;
        }

        $path = $this->normalise ($path);

        if (file_exists ($path)) {
        } else {
            throw new FileException
            ('Filesystem: chmod method. File does not exist' . $path);
        }

        if (in_array ($mode, array('0600', '0644', '0755', '0750'))) {
        } else {
            throw new FileException
            ('Filesystem: chmod method. Mode not provided: ' . $mode);
        }

        if () {
            try {
                chmod ($path, $mode);

            } catch (Exception $e) {
                throw new FileException

                ('Filesystem: chmod method failed for ' . $mode);
            }
        }

        return $mode;
    }

    /**
     * Update the touch time and/or the access time for the directory or file identified in the path
     *
     * @param   string  $path
     * @param   int     $time
     * @param   int     $atime
     *
     * @return  null
     * @since   1.0
     */
    public function touch ($path, $time, $atime = null)
    {
        if ($path == '') {
            $path = $this->path;
        }

        $path = $this->normalise ($path);

        if (file_exists ($path)) {
        } else {
            throw new FileException
            ('Filesystem: setModifiedDate method. File does not exist' . $path);
        }

        if ($time == '' || $time === null || $time == 0) {
            $time = time ();
        }

        try {

            if (touch (path, $time)) {
                echo $path . ' modification time has been changed to present time';
            } else {
                echo 'Sorry, could not change modification time of ' . $path;
            }

        } catch (Exception $e) {
            throw new FileException

            ('Filesystem: is_readable method failed for ' . $path);
        }

        return $time;
    }
}
