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

use \Exception;
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
abstract class FilesystemAdapter implements FileInterface, FilesystemInterface
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
     * FileInterface Elements
     */

    /**
     * Returns the value 'directory, 'file' or 'link' for the type determined
     *  from the path
     *
     * @param   string  $path
     *
     * @return  null
     * @since   1.0
     * @throws  FileException
     */
    public function getType ($path)
    {
        if ($path == '') {
            $path = $this->path;
        }

        $path = $this->normalise ($path);

        if ($this->exists ($path) === false) {
            throw new FileNotFoundException
                ('Filesystem: getName method does not exist: ' . $path);
        }

        if ($this->isDirectory ($path)) {
            return 'directory';
        }

        if ($this->isFile ($path)) {
            return 'file';
        }

        if ($this->isLink ($path)) {
            return 'link';
        }

        throw new FileException ('Not a directory, file or a link.');
    }

    /**
     * Returns true or false indicator as to whether or not the path is a directory
     *
     * @return  bool
     * @since   1.0
     */
    public function isDirectory ($path)
    {
        if ($path == '') {
            $path = $this->path;
        }

        $path = $this->normalise ($path);

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
        if ($path == '') {
            $path = $this->path;
        }

        $path = $this->normalise ($path);

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
        if ($path == '') {
            $path = $this->path;
        }

        $path = $this->normalise ($path);

        if (is_link ($path)) {
            return true;
        }

        return false;
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
        if ($path == '') {
            $path = $this->path;
        }

        $path = $this->normalise ($path);

        if (file_exists ($path)) {
            return true;
        }

        return false;
    }

    /**
     * Get File Name
     *
     * @param   string $path
     *
     * @return  bool|null
     * @since   1.0
     */
    public function getName ($path)
    {
        if ($path == '') {
            $path = $this->path;
        }

        $path = $this->normalise ($path);

        if ($this->exists ($path) === false) {
            throw new FileNotFoundException
            ('Filesystem: getName method Path does not exist: ' . $path);
        }

        return getBasename($path);
    }

    /**
     * Get File Extension
     *
     * @param   string $path
     *
     * @return  bool|null
     * @since   1.0
     */
    public function getExtension ($path)
    {
        if ($path == '') {
            $path = $this->path;
        }

        $path = $this->normalise ($path);

        if ($this->exists ($path) === false) {
            throw new FileNotFoundException
            ('Filesystem: getExtension method Path does not exist: ' . $path);
        }

        $path = $this->normalise ($path);

        if ($this->exists ($path) === false) {
            throw new FileNotFoundException ('Filesystem: could not find file at path: ', $path);
        }

        if ($this->isFile ($path)) {
        } else {
            throw new FileNotFoundException ('Filesystem: not a valid file path: ', $path);
        }

        return pathinfo(basename($path), PATHINFO_EXTENSION);
    }

    /**
     * Get Mime Type
     *
     * @param   string $path
     *
     * @return  bool|null
     * @since   1.0
     */
    public function getMimeType ($path)
    {
        if (file_exists ($path)) {
            return true;
        }


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

            if ($this->isWriteable($path . '/' . $file) === false) {
                throw new FileException
                ('Filesystem: file is not writable: ' . $path . '/' . $file);
            }

            $handle = fopen($path . '/' . $file,'r+');

            if(flock($handle, LOCK_EX)) {
            } else {
                throw new FileException
                ('Filesystem: Lock not obtainable for file write for: ' . $path . '/' . $file);
            }

            fclose($handle);
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

            if ($this->isWriteable($path) === false) {
                throw new FileException
                ('Filesystem: file to be deleted is not writable: ' . $path);
            }

            $handle = fopen($path, 'r+');

            if(flock($handle, LOCK_EX)) {
            } else {
                throw new FileException
                ('Filesystem: Lock not obtainable for delete for: ' . $path);
            }

            fclose($handle);
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
        $class                   = 'Molajo\\Filesystem\\File';
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

        return $path;
    }

    /**
     * Get the file size of a given file.
     *
     * @param   string  $path
     *
     * @return  int
     * @since   1.0
     */
    public function size ($path = '')
    {
        if ($path == '') {
            $path = $this->path;
        }

        return filesize ($path);
    }

    /**
     * FilesystemInterface
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
        return fileowner($path);
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
        return $this->group;
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


    }

    /**
     * Retrieves Last Update Date for directory or file identified in the path
     *
     * @param   string  $path
     *
     * @return  null
     * @since   1.0
     */
    public function getUpdateDate ($path)
    {
        if ($path == '') {
            $path = $this->path;
        }


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
        return is_readable($path);
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
        return is_writable($path);
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
        return is_executable($path);
    }

    /**
     * Changes the owner to the value specified for the file or directory defined in the path
     *
     * @param   string  $path
     * @param   string  $owner
     *
     * @return  string
     * @since   1.0
     */
    public function setOwner ($path, $owner)
    {
        $this->owner = $owner;

        return $this->owner;
    }

    /**
     * Changes the group to the value specified for the file or directory defined in the path
     *
     * @param   string  $path
     * @param   string  $group
     *
     * @return  null
     * @since   1.0
     */
    public function setGroup ($path, $group)
    {
        $this->group = $group;

        return $this->group;
    }

    /**
     * Set read access to true or false for the group specified: 'owner', 'group', or 'world'
     *
     * @param   string  $path
     * @param   null    $group
     * @param   bool    $permission
     *
     * @return  null
     * @since   1.0
     */
    public function setReadable ($path, $group = null, $permission = true)
    {

    }

    /**
     * Set write access to true or false for the group specified: 'owner', 'group', or 'world'
     *
     * @param   string  $path
     * @param   null    $group
     * @param   bool    $permission
     *
     * @return  null
     * @since   1.0
     */
    public function setWriteable ($path, $group = null, $permission = true)
    {

    }

    /**
     * Set execute access to true or false for the group specified: 'owner', 'group', or 'world'
     *
     * @param   string  $path
     * @param   null    $group
     * @param   bool    $permission
     *
     * @return  null
     * @since   1.0
     */
    public function setExecutable ($path, $group = null, $permission = true)
    {

    }

    /**
     * Sets the Last Access Date for directory or file identified in the path
     *
     * @param   string  $path
     * @param   string  $value
     *
     * @return  null
     * @since   1.0
     */
    public function setAccessDate ($path, $value)
    {

    }

    /**
     * Sets the Last Update Date for directory or file identified in the path
     *
     * @param   string  $path
     * @param   string  $value
     *
     * @return  null
     * @since   1.0
     */
    public function setUpdateDate ($path, $value)
    {

    }
}
