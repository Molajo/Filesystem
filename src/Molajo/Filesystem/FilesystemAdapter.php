<?php
/**
 * Base Adapter for Filesystem
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem;

defined ('MOLAJO') or die;

use Molajo\Filesystem\FileInterface;

use Exception;
use RuntimeException;
use Molajo\Filesystem\Exception\InvalidPathException as InvalidPathException;
use Molajo\Filesystem\Exception\FileException as FileException;
use Molajo\Filesystem\Exception\AdapterNotFoundException as AdapterNotFoundException;

/**
 * Base Adapter for Filesystem
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
abstract class FilesystemAdapter implements FileInterface
{
    /**
     * Options
     *
     * @var    array
     * @since  1.0
     */
    protected $options;

    /**
     * Path
     *
     * @var    string
     * @since  1.0
     */
    protected $path;

    /**
     * Type: Directory,
     *
     * @var    string
     * @since  1.0
     */
    protected $type;

    /**
     * Absolute Path for Filesystem
     *
     * @var    string
     * @since  1.0
     */
    protected $absolute_path;

    /**
     * Root Directory for Filesystem
     *
     * @var    string
     * @since  1.0
     */
    protected $root;

    /**
     * Persistence
     *
     * @var    bool
     * @since  1.0
     */
    protected $persistence;

    /**
     * Constructor
     *
     * @param   array  $options
     *
     * @since   1.0
     */
    public function __construct ($path, $options = array())
    {
        $this->options = $options;

        if (isset($this->options['root'])) {
            $this->setRoot ($this->options['root']);
        } else {
            $this->setRoot ('/');
        }

        if (isset($this->options['persistence'])) {
            $this->setPersistence ($this->options['persistence']);
        } else {
            $this->setPersistence (0);
        }

        $this->setPath ($path);

        return;
    }

    /**
     * Set the Path
     *
     * @param   string  $path
     *
     * @return  string
     * @since   1.0
     */
    public function setPath ($path)
    {
        $path = $this->normalise ($path);

        return $this->getAbsolutePath ($path);
    }

    /**
     * Retrieves the absolute path, which is the relative path from the root directory,
     *  prepended with a '/'.
     *
     * @return  string
     * @since   1.0
     */
    public function getAbsolutePath ($path)
    {
        $this->absolute_path = realpath ($path);

        return $this->absolute_path;
    }

    /**
     * Set Root of Filesystem
     *
     * @param   string  $root
     *
     * @return  mixed
     * @since   1.0
     */
    public function setRoot ($root)
    {
        $this->root = rtrim ($root, '/\\') . '/';

        return $this->root;
    }

    /**
     * Set persistence indicator for Filesystem
     *
     * @param   bool  $persistence
     *
     * @return  null
     * @since   1.0
     */
    public function setPersistence ($persistence)
    {
        $this->persistence = $persistence;

        return $this->persistence;
    }

    /**
     * Returns the value 'directory, 'file' or 'link' for the type determined
     *  from the path
     *
     * @param   string  $path
     *
     * @return  null
     * @since   1.0
     */
    public function getType ($path)
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

        throw new FileException ('Not a directory, file or a link.');
    }

    /**
     * Does the path exist (either as a file or a folder)?
     *
     * @param string $path
     *
     * @return bool|null
     */
    public function exists ($path)
    {
        if (file_exists ($path)) {
            return true;
        }

        return false;
    }

    /**
     * Returns the contents of the file located at path directory
     *
     * @param   string  $path
     *
     * @return  mixed
     * @since   1.0
     * @throws  FileException when file does not exist
     * @throws  RuntimeException when unable to read file
     */
    public function read ($path = '')
    {
        $path = $this->normalise ($path);

        if ($this->exists ($path) === false) {
            throw new FileException ('Could not find file at path: ', $path);
        }

        if ($this->isFile ($path)) {
        } else {
            throw new FileException ('Is not a file path: ', $path);
        }

        $data = false;

        try {
            $data = file_get_contents ($path);

        } catch (\Exception $e) {

            throw new \Exception
            ('Filesystem Read: Error reading path ' . $path);
        }

        if ($data === false) {
            throw new FileException ('Could not read: ', $path);
        }

        return $data;
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
    public function createDirectory ($path, $new_name, $replace = false, $create_directories = true)
    {
        $path = $this->normalise ($path);

        if ($replace === false) {
            if (file_exists ($path)) {
                return false;
            }
        }

        if ($create_directories === false) {
            if (file_exists ($path)) {
            } else {
                return false;
            }
        }

        if (file_exists ($path)) {
            return file_get_contents ($path);
        }

        \mk_dir ($path);

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
     * Creates or replaces the file identified in path using the data value
     *
     * @param   string  $path
     * @param   string  $file
     * @param           $data
     * @param   bool    $replace
     * @param   bool    $create_directories
     *
     * @return  void
     * @since   1.0
     */
    function write ($path, $file, $data, $replace = false, $create_directories = true)
    {
        $path = $this->normalise ($path);

        if ($replace === false) {
            if (file_exists ($path)) {
                return false;
            }
        }

        if ($create_directories === false) {
            if (file_exists ($path)) {
            } else {
                return false;
            }
        }

        if (file_exists ($path)) {
            return file_get_contents ($path);
        }

        $numBytes = \file_put_contents ($path, $data);

        if (false === $numBytes || (int) $numBytes == 0) {
            throw new FileSystemException(sprintf ('Could not write the "%s" key content.', $path));
        }

        return;
    }

    /**
     * Copies the file identified in $path to the target_adapter in the new_parent_directory,
     *  replacing existing contents, if indicated, and creating directories needed, if indicated
     *
     * Note: $target_filesystem is an instance of the Filesystem exclusive to the target portion of the copy
     *
     * @param   string    $path
     * @param   object    $target_filesystem
     * @param   string    $target_directory
     * @param   bool      $replace
     * @param   bool      $create_directories
     *
     * @return  null
     * @since   1.0
     */
    public function copy ($path, $target_filesystem, $target_directory, $replace = false, $create_directories = true)
    {
        $options            = array();
        $options['adapter_name'] =  $target_filesystem;
        $class = 'Molajo\\Filesystem\\File';
        $target = new $class($target_directory, $options);

        $data = $this->read ($path);

        $results = $target->write ($target_directory, basename($path), $data, $replace, $create_directories);

        if ($results === false) {
            throw new \FileSystemException('Could not write the "%s" key content.',
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
     * @param   bool    $create_directories
     *
     * @return  null
     * @since   1.0
     */
    public function move ($path, $target_filesystem, $target_directory, $replace = false, $create_directories = true)
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
        $path = $this->normalise ($path);

        if (file_exists ($path)) {
            return unlink ($path);
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

        return $path;
    }

    /**
     * Method to connect to a Local server
     *
     * @return  object|resource
     * @since   1.0
     * @throws  \Exception
     */
    public function connect ()
    {
    }

    /**
     * Method to login to a server once connected
     *
     * @return  bool
     * @since   1.0
     * @throws  \RuntimeException
     */
    public function login ()
    {
    }

    /**
     * Destruct Method
     *
     * @return  void
     * @since   1.0
     */
    public function __destruct ()
    {
    }

    /**
     * Close the Local Connection
     *
     * @return  void
     * @since   1.0
     * @throws  \Exception
     */
    public function close ()
    {
    }

    /**
     * Get the file size of a given file.
     *
     * @param string $path
     *
     * @return int
     */
    public function size ($path)
    {
        return filesize ($path);
    }

    /**
     * Retrieves Create Date for directory or file identified in the path
     *
     * @return  null
     * @since   1.0
     */
    public function getCreateDate ()
    {

    }

    /**
     * Retrieves Last Access Date for directory or file identified in the path
     *
     * @return  null
     * @since   1.0
     */
    public function getAccessDate ()
    {

    }

    /**
     * Retrieves Last Update Date for directory or file identified in the path
     *
     * @return  null
     * @since   1.0
     */
    public function getUpdateDate ()
    {
        return filemtime ($this->path);
    }

    /**
     * Sets the Last Access Date for directory or file identified in the path
     *
     * @return  null
     * @since   1.0
     */
    public function setAccessDate ()
    {

    }

    /**
     * Sets the Last Update Date for directory or file identified in the path
     *
     * @param   string  $value
     *
     * @return  null
     * @since   1.0
     */
    public function setUpdateDate ($value)
    {

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
    public function isAbsolute ($path)
    {
        if (substr ($path, 0, 1) == '/') {
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
     * Returns true or false indicator as to whether or not the path is a directory
     *
     * @return  bool
     * @since   1.0
     */
    public function isDirectory ($path)
    {
        if (is_file ($path)) {
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
    public function isFile ($path)
    {
        if (is_file ($path)) {
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
    public function isLink ($path)
    {
        if (is_link ($path)) {
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
        //$this->group       = $path;
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

}
