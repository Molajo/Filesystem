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
        if ($path == '') {
            $path = $this->path;
        }

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
    function write ($path = '', $file, $data, $replace)
    {
        if ($path == '') {
            $path = $this->path;
        }

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
    public function delete ($path = '', $delete_empty_directory = true)
    {
        if ($path == '') {
            $path = $this->path;
        }

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
    public function copy ($path = '', $target_filesystem, $target_directory, $replace = false)
    {
        if ($path == '') {
            $path = $this->path;
        }
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
    public function move ($path = '', $target_filesystem, $target_directory, $replace = false)
    {
        if ($path == '') {
            $path = $this->path;
        }

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
    public function getList ($path = '')
    {
        if ($path == '') {
            $path = $this->path;
        }

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
    public function createDirectory ($path = '', $new_name, $replace = false)
    {
        if ($path == '') {
            $path = $this->path;
        }

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
    public function deleteDirectory ($path = '', $delete_subdirectories = true)
    {
        if ($path == '') {
            $path = $this->path;
        }

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
    public function getMetadata ($path = '', $options)
    {

    }
}
