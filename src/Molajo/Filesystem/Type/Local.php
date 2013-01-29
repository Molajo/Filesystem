<?php
/**
 * Local Adapter for Filesystem
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem\Type;

defined('MOLAJO') or die;

/**
 * Local Adapter for Filesystem
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class Local
{
    /**
     * Filesystem Type
     *
     * @var    string
     * @since  1.0
     */
    public $filesystem_type;

    /**
     * Root Directory for Filesystem
     *
     * @var    string
     * @since  1.0
     */
    public $root;

    /**
     * Persistence
     *
     * @var    bool
     * @since  1.0
     */
    public $persistence;

    /**
     * Directory Permissions
     *
     * @var    string
     * @since  1.0
     */
    public $directory_permissions;

    /**
     * File Permissions
     *
     * @var    string
     * @since  1.0
     */
    public $file_permissions;

    /**
     * Read only
     *
     * @var    string
     * @since  1.0
     */
    public $read_only;

    /**
     * Owner
     *
     * @var    string
     * @since  1.0
     */
    public $owner;

    /**
     * Group
     *
     * @var    string
     * @since  1.0
     */
    public $group;

    /**
     * Create Date
     *
     * @var    date
     * @since  1.0
     */
    public $create_date;

    /**
     * Access Date
     *
     * @var    date
     * @since  1.0
     */
    public $access_date;

    /**
     * Modified Date
     *
     * @var    date
     * @since  1.0
     */
    public $modified_date;

    /**
     * Is Readable
     *
     * @var    bool
     * @since  1.0
     */
    public $is_readable;

    /**
     * Is Writable
     *
     * @var    bool
     * @since  1.0
     */
    public $is_writable;

    /**
     * Is Executable
     *
     * @var    bool
     * @since  1.0
     */
    public $is_executable;

    /**
     * Path
     *
     * @var    string
     * @since  1.0
     */
    public $path;

    /**
     * Exists
     *
     * @var    bool
     * @since  1.0
     */
    public $exists;

    /**
     * Absolute Path for Filesystem
     *
     * @var    string
     * @since  1.0
     */
    public $absolute_path;

    /**
     * Is Absolute Path
     *
     * @var    bool
     * @since  1.0
     */
    public $is_absolute;

    /**
     * Is Directory
     *
     * @var    bool
     * @since  1.0
     */
    public $is_directory;

    /**
     * Is File
     *
     * @var    bool
     * @since  1.0
     */
    public $is_file;

    /**
     * Is Link
     *
     * @var    bool
     * @since  1.0
     */
    public $is_link;

    /**
     * Type: Directory, File, Link
     *
     * @var    string
     * @since  1.0
     */
    public $type;

    /**
     * File name
     *
     * @var    string
     * @since  1.0
     */
    public $name;

    /**
     * Parent
     *
     * @var    string
     * @since  1.0
     */
    public $parent;

    /**
     * Extension
     *
     * @var    string
     * @since  1.0
     */
    public $extension;

    /**
     * Size
     *
     * @var    string
     * @since  1.0
     */
    public $size;

    /**
     * Mime Type
     *
     * @var    string
     * @since  1.0
     */
    public $mime_type;

    /**
     * Options
     *
     * @var    array
     * @since  1.0
     */
    public $options;

    /**
     * File contents
     *
     * @var    string
     * @since  1.0
     */
    public $file_content;

    /**
     * Constructor
     *
     * @since  1.0
     */
    public function __construct()
    {
        $this->filesystem_type = 'Local';

        return $this;
    }

    /**
     * Method to connect to a Local server
     *
     * @return  object|resource
     * @since   1.0
     */
    public function connect()
    {
        $this->root = $this->setRoot();

        $this->persistence = $this->setPersistence();

        $this->setDefaultPermissions();

        return $this;
    }

    /**
     * Set Root of Filesystem
     *
     * @param   string  $root
     *
     * @return  string
     * @since   1.0
     */
    public function setRoot($root = '')
    {
        if ($root == '') {
            $root = ROOT_FOLDER;
        }

        $this->root = $this->normalise($root);

        return $this->root;
    }

    /**
     * Set persistence indicator for Filesystem
     *
     * @param   bool  $persistence
     *
     * @return  bool
     * @since   1.0
     */
    public function setPersistence($persistence = 1)
    {
        if ($persistence == 1) {
        } else {
            $persistence = 0;
        }

        $this->persistence = $persistence;

        return $this->persistence;
    }

    /**
     * Set default permissions
     *
     * @param   string  $directory_permissions
     * @param   string  $file_permissions
     * @param   int     $read_only
     *
     * @return  void
     * @since   1.0
     */
    public function setDefaultPermissions(
        $directory_permissions = '0755',
        $file_permissions = '0644',
        $read_only = 1
    ) {
        if ((int)$directory_permissions == 0) {
            $directory_permissions = '0755';
        }
        $this->directory_permissions = $directory_permissions;

        if ((int)$file_permissions == 0) {
            $file_permissions = '0755';
        }
        $this->file_permissions = $file_permissions;

        if ((int)$read_only == 0) {
        } else {
            $read_only = 1;
        }
        $this->read_only = $read_only;

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
    public function setPath($path = '')
    {
        if ($path == '') {
            $path = $this->path;
        }

        $this->path = $this->normalise($path);

        return $this->path;
    }

    /**
     * Get the Path
     *
     * @return  string
     * @since   1.0
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * get Root of Filesystem
     *
     * @return  string
     * @since   1.0
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * Get Filesystem Type
     *
     * @return  string
     * @since   1.0
     */
    public function getFilesystemType()
    {
        return $this->filesystem_type;
    }

    /**
     * Returns the owner of the file or directory defined in the path
     *
     * @return  bool
     * @since   1.0
     */
    public function getOwner()
    {
        $this->owner = fileowner($this->path);

        return $this->owner;
    }

    /**
     * Returns the group for the file or directory defined in the path
     *
     * @return  string
     * @since   1.0
     */
    public function getGroup()
    {
        $this->group = filegroup($this->path);

        return $this->group;
    }

    /**
     * Retrieves Create Date for directory or file identified in the path
     *
     * @return  null
     * @since   1.0
     * @throws  FileException
     */
    public function getCreateDate()
    {
        try {
            $this->create_date = date("F d Y H:i:s.", filectime($this->path));

        } catch (Exception $e) {
            throw new FileException

            ('Filesystem: getCreateDate method failed for ' . $this->path);
        }

        return $this->create_date;
    }

    /**
     * Retrieves Last Access Date for directory or file identified in the path
     *
     * @return  null
     * @since   1.0
     * @throws  FileException
     */
    public function getAccessDate()
    {
        try {
            $this->access_date = \fileatime("F d Y H:i:s.", filectime($this->path));

        } catch (Exception $e) {
            throw new FileException

            ('Filesystem: getCreateDate method failed for ' . $this->path);
        }

        return $this->access_date;
    }

    /**
     * Retrieves Last Update Date for directory or file identified in the path
     *
     * @return  null
     * @since   1.0
     * @throws  FileException
     */
    public function getModifiedDate()
    {
        try {
            $this->modified_date = \filemtime("F d Y H:i:s.", filemtime($this->path));

        } catch (Exception $e) {
            throw new FileException

            ('Filesystem: getModifiedDate method failed for ' . $this->path);
        }

        return $this->modified_date;
    }

    /**
     * Tests if the group specified: 'owner', 'group', or 'world' has read access
     *  Returns true or false
     *
     * @param   string  $path
     *
     * @return  bool
     * @since   1.0
     */
    public function isReadable()
    {
        $this->is_readable = is_readable($this->path);

        return $this->is_readable;
    }

    /**
     * Tests if the group specified: 'owner', 'group', or 'world' has write access
     *  Returns true or false
     *
     * @param   string  $path
     *
     * @return  bool
     * @since   1.0
     */
    public function isWriteable()
    {
        $this->is_writeable = is_writable($this->path);

        return $this->is_writeable;
    }

    /**
     * Tests if the group specified: 'owner', 'group', or 'world' has execute access
     *  Returns true or false
     *
     * @param   string  $path
     *
     * @return  null
     * @since   1.0
     */
    public function isExecutable()
    {
        $this->is_executable = is_executable($this->path);

        return $this->is_executable;
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
     * @param   string  $path
     * @param   int     $mode
     *
     * @return  int|null
     * @throws  FileException
     * @since   1.0
     */
    public function chmod($mode)
    {
        if (in_array($mode, array('0600', '0644', '0755', '0750'))) {
        } else {

            throw new FileException
            ('Filesystem: chmod method. Mode not provided: ' . $mode);
        }

        try {
            chmod($this->path, $mode);

        } catch (Exception $e) {

            throw new FileException
            ('Filesystem: chmod method failed for ' . $mode);
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
     * @return  int|null
     * @throws  FileException
     * @since   1.0
     */
    public function touch($time, $atime = null)
    {
        if ($time == '' || $time === null || $time == 0) {
            $time = getDateTime($time);
        }

        try {

            if (touch($this->path, $time)) {
                echo $path . ' modification time has been changed to present time';

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
     * Get Date Time
     *
     * @param   $time
     *
     * @return  DateTime
     * @since   1.0
     */
    public function getDateTime($time)
    {

        if ($time instanceof DateTime) {
            return $time;
        }

        if (is_int($time) || is_float($time)) {
            return new DateTime('@' . intval($time));
        }

        return new DateTime($time);
    }

    /**
     * Retrieves the absolute path, which is the relative path from the root directory,
     *  prepended with a '/'.
     *
     * @param   string  $path
     *
     * @return  string
     * @since   1.0
     * @throws  FileNotFoundException
     */
    public function getAbsolutePath($path = '')
    {
        if ($path == '') {
            $path = $this->path;
        }

        $path = $this->normalise($path);

        if ($this->exists($path) === false) {
            throw new FileNotFoundException
            ('Filesystem: getAbsolutePath method does not exist: ' . $path);
        }

        $this->absolute_path = realpath($path);

        return $this->absolute_path;
    }

    /**
     * Indicates whether the given path is absolute or not
     *
     * Relative path - describes how to get from a particular directory to a file or directory
     * Absolute Path - relative path from the root directory, prepended with a '/'.
     *
     * @param   string  $path
     *
     * @return  bool
     * @since   1.0
     */
    public function isAbsolute($path = '')
    {
        if (substr($this->path, 0, 1) == '/') {
            return true;
        }

        return false;
    }

    /**
     * Returns the value 'directory, 'file' or 'link' for the type determined
     *  from the path
     *
     * @param   string  $path
     *
     * @return  null
     * @since   1.0
     * @throws  FileException
     * @throws  FileNotFoundException
     */
    public function getType($path = '')
    {
        if ($path == '') {
            $path = $this->path;
        }

        $path = $this->normalise($path);

        if ($this->exists($path) === false) {
            throw new FileNotFoundException
            ('Filesystem: getName method does not exist: ' . $path);
        }

        if ($this->isDirectory($path)) {
            return 'directory';
        }

        if ($this->isFile($path)) {
            return 'file';
        }

        if ($this->isLink($path)) {
            return 'link';
        }

        throw new FileException ('Not a directory, file or a link.');
    }

    /**
     * Returns true or false indicator as to whether or not the path is a directory
     *
     * @param   string  $path
     *
     * @return  bool
     * @since   1.0
     */
    public function isDirectory($path = '')
    {
        if ($path == '') {
            $path = $this->path;
        }

        $path = $this->normalise($path);

        if (is_file($path)) {
            return true;
        }

        return false;
    }

    /**
     * Returns true or false indicator as to whether or not the path is a file
     *
     * @param   string  $path
     *
     * @return  bool
     * @since   1.0
     */
    public function isFile($path = '')
    {
        if ($path == '') {
            $path = $this->path;
        }

        $path = $this->normalise($path);

        if (is_file($path)) {
            return true;
        }

        return false;
    }

    /**
     * Returns true or false indicator as to whether or not the path is a link
     *
     * @param   string  $path
     *
     * @return  bool
     * @since   1.0
     */
    public function isLink($path = '')
    {
        if ($path == '') {
            $path = $this->path;
        }

        $path = $this->normalise($path);

        if (is_link($path)) {
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
    public function exists($path = '')
    {
        if ($path == '') {
            $path = $this->path;
        }

        $path = $this->normalise($path);

        if (file_exists($path)) {
            return true;
        }

        return false;
    }

    /**
     * Get File or Directory Name
     *
     * @param   string $path
     *
     * @return  bool|null
     * @since   1.0
     * @throws  FileNotFoundException
     */
    public function getName($path = '')
    {
        if ($path == '') {
            $path = $this->path;
        }

        $path = $this->normalise($path);

        if ($this->exists($path) === false) {
            throw new FileNotFoundException
            ('Filesystem: getName method Path does not exist: ' . $path);
        }

        return basename($path);
    }

    /**
     * Get Parent
     *
     * @param   string $path
     *
     * @return  bool|null
     * @since   1.0
     * @throws  FileNotFoundException
     */
    public function getParent($path = '')
    {
        if ($path == '') {
            $path = $this->path;
        }

        $path = $this->normalise($path);

        if ($this->exists($path) === false) {
            throw new FileNotFoundException
            ('Filesystem: getParent method Path does not exist: ' . $path);
        }

        return dirname($path);
    }

    /**
     * Get File Extension
     *
     * @param   string $path
     *
     * @return  bool|null
     * @since   1.0
     * @throws  FileNotFoundException
     */
    public function getExtension($path = '')
    {
        if ($path == '') {
            $path = $this->path;
        }

        $path = $this->normalise($path);

        if ($this->exists($path) === false) {
            throw new FileNotFoundException
            ('Filesystem: getExtension method Path does not exist: ' . $path);
        }

        $path = $this->normalise($path);

        if ($this->exists($path) === false) {
            throw new FileNotFoundException ('Filesystem: could not find file at path: ', $path);
        }

        if ($this->isFile($path)) {
        } else {
            throw new FileNotFoundException ('Filesystem: not a valid file path: ', $path);
        }

        return pathinfo(basename($path), PATHINFO_EXTENSION);
    }

    /**
     * Get the file size of a given file.
     *
     * @param   string  $path
     *
     * @return  int
     * @since   1.0
     */
    public function size($path = '')
    {
        if ($path == '') {
            $path = $this->path;
        }

        return filesize($path);
    }

    /**
     * Normalizes the given path
     *
     * @param   $path
     *
     * @return  string
     * @since   1.0
     * @throws  OutOfBoundsException
     */
    public function normalise($path = '')
    {
        if ($path == '') {
            $path = $this->path;
        }

        $absolute_path = false;

        if (substr($path, 0, 1) == '/') {
            $absolute_path = true;
            $path          = substr($path, 1, strlen($path));
        }

        /** Unescape slashes */
        $path = str_replace('\\', '/', $path);

        /**  Filter: empty value
         *
         * @link http://tinyurl.com/arrayFilterStrlen
         */
        $nodes = array_filter(explode('/', $path), 'strlen');

        $normalised = array();

        foreach ($nodes as $node) {

            /** '.' means current - ignore it      */
            if ($node == '.') {

                /** '..' is parent - remove the parent */
            } elseif ($node == '..') {

                if (count($normalised) > 0) {
                    array_pop($normalised);
                }

            } else {
                $normalised[] = $node;
            }

        }

        $path = implode('/', $normalised);
        if ($absolute_path === true) {
            $path = '/' . $path;
        }

        return $path;
    }

    /**
     * Returns true or false indicator as to whether or not the path is a link
     *
     * @param   string  $path
     *
     * @return  bool
     * @since   1.0
     */
    public function getSize()
    {
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
    public function read($path = '')
    {
        if ($path == '') {
            $path = $this->path;
        }

        $path = $this->normalise($path);

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
    function write($path = '', $file, $data, $replace)
    {
        if ($path == '') {
            $path = $this->path;
        }

        $path = $this->normalise($path);

        if (trim($data) == '' || strlen($data) == 0) {
            throw new FileException
            ('Filesystem: attempting to write no data to file: ' . $path . '/' . $file);
        }

        if (file_exists($path . '/' . $file)) {

            if ($replace === false) {
                throw new FileException
                ('Filesystem: attempting to write to existing file: ' . $path . '/' . $file);
            }

            if ($this->isWriteable($path . '/' . $file) === false) {
                throw new FileException
                ('Filesystem: file is not writable: ' . $path . '/' . $file);
            }

            $handle = fopen($path . '/' . $file, 'r+');

            if (flock($handle, LOCK_EX)) {
            } else {
                throw new FileException
                ('Filesystem: Lock not obtainable for file write for: ' . $path . '/' . $file);
            }

            fclose($handle);
        }

        try {
            \file_put_contents($path . '/' . $file, $data, LOCK_EX);

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

        $path = $this->normalise($path);

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
     * Returns the contents of the file located at path directory
     *
     * @param   string  $path
     *
     * @return  mixed;
     * @since   1.0
     */
    public function getList($path = '')
    {
        if ($path == '') {
            $path = $this->path;
        }

        $path = $this->normalise($path);

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
     * Creates directory identified in path using the data value
     *
     * @param   string  $path
     * @param   string  $new_name
     * @param   bool    $replace
     *
     * @return  null
     * @since   1.0
     */
    public function createDirectory($path = '', $new_name, $replace = false)
    {
        if ($path == '') {
            $path = $this->path;
        }

        $path = $this->normalise($path);

        if ($replace === false) {
            if (file_exists($path)) {
                return false;
            }
        }

        if (file_exists($path)) {
            return file_get_contents($path);
        }

        \mk_dir($path, $this->directory_permissions, true);

        // Desired folder structure
        $structure = './depth1/depth2/depth3/';

// To create the nested structure, the $recursive parameter
// to mkdir() must be specified.

        if (! mkdir($structure, 0, true)) {
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
    public function deleteDirectory($path = '', $delete_subdirectories = true)
    {
        if ($path == '') {
            $path = $this->path;
        }

        $path = $this->normalise($path);

        if (file_exists($path)) {
            return file_get_contents($path);
        }

        \mk_dir($path);

        if ($this->isDirectory($path)) {
            return rmdir($path);
        }

        return false;
    }

    /**
     * Returns the mime type for this file.
     *
     * @throws \RuntimeException if info failed to load and/or mim_content_type
     * is unavailable
     * @throws \LogicException if the mime type could not be interpreted from
     * the output of finfo_file
     *
     * @return string
     */
    public function getMimeType()
    {
        return;

        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            if (! $finfo) {
                throw new \RuntimeException('Failed to open finfo');
            }

            $mime = strtolower(finfo_file($finfo, $this->getPathname()));
            finfo_close($finfo);

            if (! preg_match(
                '/^([a-z0-9]+\/[a-z0-9\-\.]+);\s+charset=(.*)$/',
                $mime,
                $matches
            )
            ) {
                throw new \LogicException(
                    'An error parsing the MIME type "' . $mime . '".'
                );
            }

            return $matches[1];
        } elseif (function_exists('mime_content_type')) {
            return mime_content_type($this->getPathname());
        }

        throw new \RuntimeException(
            'The finfo extension or mime_content_type function are needed to '
                . 'determine the Mime Type for this file.'
        );
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
