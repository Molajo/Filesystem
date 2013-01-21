<?php
/**
 * Filesystem Driver
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem;

defined ('MOLAJO') or die;

use Molajo\Filesystem\Api\AdapterInterface;

use \RuntimeException;
use Molajo\Filesystem\FileNotFoundException;

/**
 * Filesystem Driver
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
Class Driver implements AdapterInterface
{
    /**
     * Adapter Instance
     *
     * @var    Adapter
     * @since  1.0
     */
    protected $adapter;

    /**
     * Options
     *
     * @var    $options
     * @since  1.0
     */
    protected $options = array();

    /**
     * Path
     *
     * @var    string
     * @since  1.0
     */
    protected $path;

    /**
     * Absolute path for current path
     *
     * An absolute path is a relative path from the root directory, prepended with a '/'.
     *
     * @var    string
     * @since  1.0
     */
    protected $absolute_path;

    /**
     * Construct
     *
     * @param   array  $options
     *
     * @since   1.0
     */
    public function __construct ($options)
    {
        $this->options = $options;

        return;
    }

    /**
     * Checks to see if the path exists
     *
     * @return  boolean
     */
    public function exists ()
    {
        return file_exists ($this->path);
    }

    /**
     * Returns the contents of the file located at path directory
     *
     * @param   string  $path
     *
     * @return  mixed;
     * @since   1.0
     * @throws  FileNotFound when file does not exist
     * @throws  RuntimeException when unable to read file
     */
    public function read ($path)
    {
        $this->path = $this->adapter->normalise ($path);

        $this->adapter->exists ($path);

        $this->adapter->isFile ($path);

        if (file_exists ($this->path)) {
            $data = file_get_contents ($this->path);
        }

        if (false === $data) {
            throw new \RuntimeException('Could not read: ', $path);
        }

        return false;
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
        $this->path = $this->normalise ($path);

        if (file_exists ($this->path)) {
            return file_get_contents ($this->path);
        }

        return false;
    }

    /**
     * Creates directory identified in path using the data value
     *
     * @param   string  $path
     * @param   string  $new_name
     * @param   bool    $replace
     * @param   bool    $create_directories
     *
     * @return  null
     * @since   1.0
     */
    public function createDirectory ($path, $new_name, $replace = false,  $create_directories = true)
    {
        $this->path = $this->normalise ($path);

        if ($replace === false) {
            if (file_exists ($this->path)) {
                return false;
            }
        }

        if ($create_directories === false) {
            if (file_exists ($this->path)) {
            } else {
                return false;
            }
        }

        if (file_exists ($this->path)) {
            return file_get_contents ($this->path);
        }

        \mk_dir ($this->path);

        // Desired folder structure
        $structure = './depth1/depth2/depth3/';

// To create the nested structure, the $recursive parameter
// to mkdir() must be specified.

        if (!mkdir($structure, 0, true)) {
            die('Failed to create folders...');
        }


        return false;
    }

    /**
     * Creates or replaces the file identified in path using the data value
     *
     * @param   string  $path
     * @param   string  $file
     * @param           $data
     * @param   bool    $replace
     * @param   bool    $create_directories
     *
     * @return  null
     * @since   1.0
     */
    function write ($path, $file, $data, $replace = false, $create_directories = true)
    {
        $this->path = $this->normalise ($path);

        if ($replace === false) {
            if (file_exists ($this->path)) {
                return false;
            }
        }

        if ($create_directories === false) {
            if (file_exists ($this->path)) {
            } else {
                return false;
            }
        }

        if (file_exists ($this->path)) {
            return file_get_contents ($this->path);
        }

        \file_put_contents ($this->path, $data);


        $numBytes = $this->adapter->write ($path, $file, $data, $replace = false, $create_directories = true);

        if (false === $numBytes) {
            throw new \RuntimeException(sprintf ('Could not write the "%s" key content.', $path));
        }

        return $numBytes;

        return false;
    }

    /**
     * Copies the file identified in $path to the target_adapter in the new_parent_directory,
     *  replacing existing contents, if indicated, and creating directories needed, if indicated
     *
     * Note: File $target is an instance of the Filesystem exclusive to the target portion of the copy
     *
     * @param   string  $path
     * @param   File    $target
     * @param   string  $target_directory
     * @param   bool    $replace
     * @param   bool    $create_directories
     *
     * @return  null
     * @since   1.0
     */
    public function copy ($path, File $target, $target_directory, $replace = false, $create_directories = true)
    {
        $data = $this->read ($path);

        $target->write ($target_directory, $data, $replace, $create_directories);

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
     * @param   bool    $create_directories
     *
     * @return  null
     * @since   1.0
     */
    public function move ($path, File $target, $target_directory, $replace = false, $create_directories = true)
    {
        $data = $this->read ($path);

        $target->write ($target_directory, $data, $replace, $create_directories);

        $this->delete ($path);

        return;
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
        $this->path = $this->normalise ($path);

        if (file_exists ($this->path)) {
            return unlink ($this->path);
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
    public function setMetadata ($path, $options)
    {

    }

    //
    //  Helper methods
    //

    /**
     * Normalizes the given path
     *
     * @param   $path
     *
     * @return  string
     * @since   1.0
     */
    public function normalise ($path)
    {
        $absolute_path = false;
        if (substr ($path, 0, 1) == '/') {
            $absolute_path = true;
            $path          = substr ($path, 1, strlen ($path));
        }

        /** Unescape slashes */
        $path = str_replace ('\\', '/', $path);

        /**  Filter: empty value
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

        return $path;
    }


    /**
     * Indicates whether the given path is absolute or not
     *
     * Relative path - describes how to get from a particular directory to a file or directory
     * Absolute Path - relative path from the root directory, prepended with a '/'.
     *
     * @return  boolean
     * @since   1.0
     */
    public function isAbsolute ()
    {
        if (substr ($this->path, 0, 1) == '/') {
            return true;
        }

        return false;
    }

    /**
     * Returns a URL that can be used to identify this entry.
     *  filesystem:http://example.domain/persistent-or-temporary/path/to/file.html.
     *
     * @return  bool
     * @since   1.0
     */
    public function convertToUrl ()
    {
        return false;
    }

    /**
     * Returns the value 'directory, 'file' or 'link' for the type determined from the path
     *
     * @return  string
     * @since   1.0
     * @throws  \Molajo\Filesystem\FilesystemException
     */
    public function getType ()
    {
        if ($this->isDirectory ($this->path)) {
            return 'directory';
        }

        if ($this->isFile ($this->path)) {
            return 'file';
        }

        if ($this->isLink ($this->path)) {
            return 'link';
        }

        throw new FilesystemException
            ('Not a directory, file or a link.');
    }

    /**
     * Returns true or false indicator as to whether or not the path is a directory
     *
     * @return  bool
     * @since   1.0
     */
    public function isDirectory ()
    {
        if (is_file ($this->path)) {
            return true;
        }

        return false;
    }

    /**
     * Returns true or false indicator as to whether or not the path is a file
     *
     * @return  bool
     * @since   1.0
     */
    public function isFile ()
    {
        if (is_file ($this->path)) {
            return true;
        }

        return false;
    }

    /**
     * Returns true or false indicator as to whether or not the path is a link
     *
     * @return  bool
     * @since   1.0
     */
    public function isLink ()
    {
        if (is_link ($this->path)) {
            return true;
        }

        return false;
    }

    /**
     * Returns the owner of the file or directory defined in the path
     *
     * @return  bool
     * @since   1.0
     */
    public function getOwner ()
    {
        return $this->owner;
    }

    /**
     * Changes the owner to the value specified for the file or directory defined in the path
     *
     * @param   string $owner
     *
     * @return  string
     * @since   1.0
     */
    public function setOwner ($owner)
    {
        $this->owner = $owner;

        return $this->owner;
    }

    /**
     * Returns the group for the file or directory defined in the path
     *
     * @return  string
     * @since   1.0
     */
    public function getGroup ()
    {
        return $this->group;
    }

    /**
     * Changes the group to the value specified for the file or directory defined in the path
     *
     * @param   string  $group
     *
     * @return  null
     * @since   1.0
     */
    public function setGroup ($group)
    {
        $this->group = $group;

        return $this->group;
    }

    /**
     * Returns associative array: 'read', 'update', 'execute' as true or false
     *  for the set group: 'owner', 'group', or 'world' and the set value for path
     *
     * @return  null
     * @since   1.0
     */
    public function getPermissions ()
    {
        //set group
        // set path
        return $this->permissions;
    }

    /**
     * Using an associative array with three entries: 'read', 'update', 'execute' with a value for
     *  each entry as either 'true' or 'false' for the group specified: 'owner', 'group',  or 'world'
     *
     * @return  null
     * @since   1.0
     */
    public function setPermissions ()
    {
        //$this->permissions = array();
        //$this->group       = $this->path;
    }


    /**
     * Tests if the group specified: 'owner', 'group', or 'world' has read access
     *  Returns true or false
     *
     * @param   null    $group
     *
     * @return  null
     * @since   1.0
     */
    public function isReadable ($group = null)
    {

    }

    /**
     * Tests if the group specified: 'owner', 'group', or 'world' has write access
     *  Returns true or false
     *
     * @param   null    $group
     *
     * @return  null
     * @since   1.0
     */
    public function isWriteable ($group = null)
    {

    }

    /**
     * Tests if the group specified: 'owner', 'group', or 'world' has execute access
     *  Returns true or false
     *
     * @param   null    $group
     *
     * @return  null
     * @since   1.0
     */
    public function isExecutable ($group = null)
    {

    }

    /**
     * Set read access to true or false for the group specified: 'owner', 'group', or 'world'
     *
     * @param   null    $group
     *
     * @return  null
     * @since   1.0
     */
    public function setReadable ($group = null)
    {

    }

    /**
     * Set write access to true or false for the group specified: 'owner', 'group', or 'world'
     *
     * @param   null    $group
     *
     * @return  null
     * @since   1.0
     */
    public function setWriteable ($group = null)
    {

    }

    /**
     * Set execute access to true or false for the group specified: 'owner', 'group', or 'world'
     *
     * @param   null    $group
     *
     * @return  null
     * @since   1.0
     */
    public function setExecutable ($group = null)
    {

    }


    /**
     * Retrieves the absolute path, which is the relative path from the root directory,
     *  prepended with a '/'.
     *
     * @return  string
     * @since   1.0
     */
    public function getAbsolutePath ()
    {
        $this->absolute_path = realpath ($this->path);

        return $this->absolute_path;
    }

}
