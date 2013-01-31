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

use \DateTime;

use \DirectoryIterator;
use \RecursiveDirectoryIterator;
use \RecursiveIteratorIterator;

use \Exception;
use \RuntimeException;
use Molajo\Filesystem\Exception\FileException;
use Molajo\Filesystem\Exception\FileNotFoundException;
use Molajo\Filesystem\Adapter;

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
     * @var    Datetime
     * @since  1.0
     */
    public $create_date;

    /**
     * Access Date
     *
     * @var    Datetime
     * @since  1.0
     */
    public $access_date;

    /**
     * Modified Date
     *
     * @var    Datetime
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
     * Directories
     *
     * @var    string
     * @since  1.0
     */
    public $directories;

    /**
     * Files
     *
     * @var    string
     * @since  1.0
     */
    public $files;

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
     * @param   $type
     *
     * @return  object|resource
     * @since   1.0
     */
    public function connect($type = null)
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

        $this->root = $root;

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
        $directory_permissions = 0,
        $file_permissions = 0,
        $read_only = 1
    ) {

        $this->directory_permissions = 0755;
        $this->file_permissions      = 0644;

        if ($read_only == 1) {
        } else {
            $read_only = 0;
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
    public function setPath($path)
    {
        $this->path = $this->normalise($path);

        return $this->path;
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
            $this->path = $path;
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

                /** '..' is parent - remove  */
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
     * Get Persistence
     *
     * @return  string
     * @since   1.0
     */
    public function getPersistence()
    {
        return $this->persistence;
    }

    /**
     * Get Directory Permissions for Filesystem
     *
     * @return  bool
     * @since   1.0
     */
    public function getDirectoryPermissions()
    {
        return $this->directory_permissions;
    }

    /**
     * Get File Permissions for Filesystem
     *
     * @return  bool
     * @since   1.0
     */
    public function getFilePermissions()
    {
        return $this->file_permissions;
    }

    /**
     * Get Read Only for Filesystem
     *
     * @return  bool
     * @since   1.0
     */
    public function getReadOnly()
    {
        return $this->read_only;
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
     * Does the path exist (either as a file or a folder)?
     *
     * @param string $this->path
     *
     * @return bool|null
     */
    public function exists()
    {
        if (file_exists($this->path)) {
            $this->exists = true;
        } else {
            $this->exists = false;
        }

        return $this->exists;
    }

    /**
     * Returns the owner of the file or directory defined in the path
     *
     * @return  bool
     * @since   1.0
     */
    public function getOwner()
    {
        if ($this->exists === true) {
        } else {
            $this->owner = null;
            return $this->owner;
        }

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
        if ($this->exists === true) {
        } else {
            $this->group = null;
            return $this->group;
        }

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
        if ($this->exists === true) {
        } else {
            $this->create_date = null;
            return $this->create_date;
        }

        try {

            $this->create_date = \date("F d Y H:i:s.", filectime($this->path));

        } catch (Exception $e) {

            throw new FileException
            ('Local Filesystem: getCreateDate method failed for ' . $this->path);
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
        if ($this->exists === true) {
        } else {
            $this->access_date = null;
            return $this->access_date;
        }

        try {

            $this->access_date = \date("F d Y H:i:s.", fileatime($this->path));

        } catch (Exception $e) {

            throw new FileException
            ('Local Filesystem: getCreateDate method failed for ' . $this->path);
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
        if ($this->exists === true) {
        } else {
            $this->modified_date = null;
            return $this->modified_date;
        }

        try {

            $this->modified_date = \date("F d Y H:i:s.", filemtime($this->path));

        } catch (Exception $e) {
            throw new FileException

            ('Local Filesystem: getModifiedDate method failed for ' . $this->path);
        }

        return $this->modified_date;
    }

    /**
     * Tests if the group specified: 'owner', 'group', or 'world' has read access
     *  Returns true or false
     *
     * @param   string  $this->path
     *
     * @return  bool
     * @since   1.0
     */
    public function isReadable()
    {
        if ($this->exists === true) {
        } else {
            $this->is_readable = null;
            return $this->is_readable;
        }

        $this->is_readable = is_readable($this->path);

        return $this->is_readable;
    }

    /**
     * Tests if the group specified: 'owner', 'group', or 'world' has write access
     *  Returns true or false
     *
     * @param   string  $this->path
     *
     * @return  bool
     * @since   1.0
     */
    public function isWriteable()
    {
        if ($this->exists === true) {
        } else {
            $this->is_writable = null;
            return $this->is_writable;
        }

        $this->is_writable = is_writable($this->path);

        return $this->is_writable;
    }

    /**
     * Tests if the group specified: 'owner', 'group', or 'world' has execute access
     *  Returns true or false
     *
     * @param   string  $this->path
     *
     * @return  null
     * @since   1.0
     */
    public function isExecutable()
    {
        if ($this->exists === true) {
        } else {
            $this->is_executable = null;
            return $this->is_executable;
        }

        $this->is_executable = is_executable($this->path);

        return $this->is_executable;
    }

    /**
     * Retrieves the absolute path, which is the relative path from the root directory,
     *  prepended with a '/'.
     *
     * @return  string
     * @since   1.0
     * @throws  FileNotFoundException
     */
    public function getAbsolutePath()
    {
        if ($this->exists === false) {
            $this->absolute_path = null;
            return $this->absolute_path;
        }

        $this->absolute_path = realpath($this->path);

        return $this->absolute_path;
    }

    /**
     * Indicates whether the given path is absolute or not
     *
     * Relative path - describes how to get from a particular directory to a file or directory
     * Absolute Path - relative path from the root directory, prepended with a '/'.
     *
     * @return  bool
     * @since   1.0
     */
    public function isAbsolute()
    {
        if ($this->exists === false) {
            $this->is_absolute = null;
            return $this->is_absolute;
        }

        if (substr($this->path, 0, 1) == '/') {
            $this->is_absolute = true;
        } else {
            $this->is_absolute = false;
        }

        return $this->is_absolute;
    }

    /**
     * Returns true or false indicator as to whether or not the path is a directory
     *
     * @return  bool
     * @since   1.0
     */
    public function isDirectory()
    {
        if (is_dir($this->path)) {
            $this->is_directory = true;
        } else {
            $this->is_directory = false;
        }

        return $this->is_directory;
    }

    /**
     * Returns true or false indicator as to whether or not the path is a file
     *
     * @return  bool
     * @since   1.0
     */
    public function isFile()
    {
        if (is_file($this->path)) {
            $this->is_file = true;
        } else {
            $this->is_file = false;
        }

        return $this->is_file;
    }

    /**
     * Returns true or false indicator as to whether or not the path is a link
     *
     * @return  bool
     * @since   1.0
     */
    public function isLink()
    {
        if (is_link($this->path)) {
            $this->is_link = true;
        } else {
            $this->is_link = false;
        }

        return $this->is_link;
    }

    /**
     * Returns the value 'directory, 'file' or 'link' for the type determined
     *  from the path
     *
     * @return  null
     * @since   1.0
     * @throws  FileException
     * @throws  FileNotFoundException
     */
    public function getType()
    {
        if ($this->exists === false) {
            $this->type = null;
            return $this->type;
        }

        if ($this->is_directory === true) {
            $this->type = 'directory';
            return $this->type;
        }

        if ($this->is_file === true) {
            $this->type = 'file';
            return $this->type;
        }

        if ($this->is_link === true) {
            $this->type = 'link';
            return $this->type;
        }

        throw new FileException ('Not a directory, file or a link.');
    }

    /**
     * Get File or Directory Name
     *
     * @return  bool|null
     * @since   1.0
     * @throws  FileNotFoundException
     */
    public function getName()
    {
        if ($this->exists === false) {
            $this->name = null;
            return $this->name;
        }

        $this->name = basename($this->path);

        return $this->name;
    }

    /**
     * Get Parent
     *
     * @return  bool|null
     * @since   1.0
     * @throws  FileNotFoundException
     */
    public function getParent()
    {
        if ($this->exists === false) {
            $this->parent = null;
            return $this->parent;
        }

        $this->parent = dirname($this->path);

        return $this->parent;
    }

    /**
     * Get File Extension
     *
     * @return  bool|null
     * @since   1.0
     * @throws  FileNotFoundException
     */
    public function getExtension()
    {
        if ($this->exists === false) {
            $this->extension = null;
            return $this->extension;
        }

        if ($this->is_file === true) {

        } elseif ($this->is_directory === true) {
            $this->extension = '';
            return $this->extension;

        } else {
            throw new FileNotFoundException
            ('Filesystem Local: not a valid file. Path: ' . $this->path);
        }

        $this->extension = pathinfo(basename($this->path), PATHINFO_EXTENSION);

        return $this->extension;
    }

    /**
     * Get the file size of a given file.
     *
     * $recursive  bool  For directory, recursively calculate file calculations default true
     *
     * @return  int
     * @since   1.0
     */
    public function getSize($recursive = true)
    {
        $this->size = 0;

        $this->discovery($this->path);

        if (count($this->files) > 0) {
            foreach ($this->files as $file) {
                $this->size = $this->size + filesize($file);
            }
        }

        return $this->size;
    }

    /**
     * Returns the mime type of the file located at path directory
     *
     * @return  mixed;
     * @since   1.0
     */
    public function getMimeType()
    {
        $this->mime_type = '';

        if ($this->exists === false) {
            $this->mime_type = null;
        }

        if ($this->is_file === true) {
        } else {

            return $this->mime_type;
        }

        if (function_exists('finfo_open')) {
            $php_mime        = finfo_open(FILEINFO_MIME);
            $this->mime_type = strtolower(finfo_file($php_mime, $this->path));
            finfo_close($php_mime);

        } elseif (function_exists('mime_content_type')) {
            $this->mime_type = mime_content_type($this->path);

        } else {
            throw new \RuntimeException
            ('Filesystem Local: getMimeType either finfo_open or mime_content_type are required in PHP');
        }

        return $this->mime_type;
    }

    /**
     * Returns the contents of the file identified by path
     *
     * @return  mixed
     * @since   1.0
     * @throws  FileNotFoundException when file does not exist
     */
    public function read()
    {
        if ($this->exists === false) {
            throw new FileNotFoundException
            ('Filesystem Local Read: File does not exist: ' . $this->path);
        }

        if ($this->is_file === false) {
            throw new FileNotFoundException
            ('Filesystem Local Read: Is not a file: ' . $this->path);
        }

        if ($this->is_readable === false) {
            throw new FileNotFoundException
            ('Filesystem Local Read: No permission, not readable: ' . $this->path);
        }

        try {
            $data = file_get_contents($this->path);

        } catch (\Exception $e) {

            throw new FileNotFoundException
            ('Filesystem Local Read: Error reading file: ' . $this->path);
        }

        if ($data === false) {
            ('Filesystem Local Read: Empty File: ' . $this->path);
        }

        return $data;
    }

    /**
     * Creates or replaces the file or directory identified in path using the data value
     *
     * @param   string  $file       spaces for create directory
     * @param   bool    $replace
     * @param   string  $data       spaces for create directory
     *
     * @return  bool
     * @since   1.0
     */
    public function write($file = '', $replace = true, $data = '')
    {
        if ($this->exists === false) {

        } elseif ($this->is_file === true || $this->is_directory === true) {

        } else {
            throw new FileException
            ('Filesystem Local Write: must be directory or file: ' . $this->path . '/' . $file);
        }

        if (trim($data) == '' || strlen($data) == 0) {

            if ($file == '') {
                throw new FileException
                ('Local Filesystem: attempting to write no data to file: ' . $this->path . '/' . $file);

            } else {
                $results = $this->createDirectory($this->path . '/' . $file);
                return true;
            }
        }

        if (file_exists($this->path) ) {
        } else {
            $results = $this->createDirectory($this->path);
        }

        if (file_exists($this->path . '/' . $file)) {

            if ($replace === false) {
                throw new FileException
                ('Local Filesystem: attempting to write to existing file: ' . $this->path . '/' . $file);
            }

            \unlink($this->path . '/' . $file);
        }

        if ($this->isWriteable($this->path . '/' . $file) === false) {
            throw new FileException
            ('Local Filesystem: file is not writable: ' . $this->path . '/' . $file);
        }


        try {

            \file_put_contents($this->path . '/' . $file, $data);

        } catch (Exception $e) {

            throw new FileNotFoundException
            ('Local Filesystem: error writing file ' . $this->path . '/' . $file);
        }

        if (file_exists($this->path . '/' . $file) === false) {
            throw new FileNotFoundException ('Filesystem Write: error writing to file: ' . $this->path . '/' . $file);
        }

        return true;
    }


    /**
     * Create Directory
     *
     * @param   bool  $path
     *
     * @return  bool
     * @since   1.0
     * @throws  FileException
     */
    public function createDirectory($path)
    {
        if (file_exists($path)) {
            return true;
        }

        try {
            \mkdir($path, $this->directory_permissions, true);

            return true;

        } catch (Exception $e) {

            throw new FileException
            ('Filesystem Create Directory: error creating directory: ' . $path);
        }

        return true;
    }

    /**
     * Deletes the file identified in path. Empty directories are removed if so indicated
     *
     * @param   bool  $delete_empty  default true
     *
     * @return  bool
     * @since   1.0
     * @throws  FileException
     * @throws  FileNotFoundException
     */
    public function delete($delete_empty = true)
    {
        if (file_exists($this->path)) {
        } else {
            throw new FileNotFoundException
            ('Local Filesystem Delete: File not found ' . $this->path);
        }

        if ($this->is_writable === false) {
            throw new FileException
            ('Local Filesystem Delete: No write access to file/path: ' . $this->path);
        }

        try {

            if (file_exists($this->path)) {
            } else {
                return true;
            }

            $this->directories = array();
            $this->files       = array();

            if ($this->is_file === true) {
                $this->files[] = $this->path;
                $delete_empty  = false;
            } else {
                $this->discovery($this->path);
            }

            if (count($this->files) > 0) {
                foreach ($this->files as $file) {
                    unlink($file);
                }
            }

            if (count($this->directories) > 0 || $delete_empty === true) {
                arsort($this->directories);
                foreach ($this->directories as $directory) {
                    rmdir($directory);
                }
            }

        } catch (Exception $e) {

            throw new FileException
            ('Local Filesystem Delete: failed for: ' . $this->path);
        }

        return true;
    }

    /**
     * Copies the file identified in $path to the target_adapter in the new_parent_name_directory,
     *  replacing existing contents, if indicated, and creating directories needed, if indicated
     *
     * Note: $target_filesystem_type is an instance of the Filesystem exclusive to the target portion of the copy
     *
     * @param   string  $target_directory
     * @param   string  $target_name
     * @param   bool    $replace
     * @param   string  $target_filesystem_type
     *
     * @return  bool
     * @since   1.0
     */
    public function copy($target_directory, $target_name = '', $replace = false, $target_filesystem_type = '')
    {
        if ($target_filesystem_type == '') {
            $target_filesystem_type = $this->filesystem_type;
        }

        return $this->moveOrCopy(
            $target_directory,
            $target_name,
            $replace,
            $target_filesystem_type,
            'copy'
        );
    }

    /**
     * Moves the file identified in path to the location identified in the new_parent_name_directory
     *  replacing existing contents, if indicated, and creating directories needed, if indicated
     *
     * @param   string  $target_directory
     * @param   string  $target_name
     * @param   bool    $replace
     * @param   string  $target_filesystem_type
     *
     * @return  bool
     * @since   1.0
     */
    public function move($target_directory, $target_name = '', $replace = false, $target_filesystem_type = '')
    {
        if ($target_filesystem_type == '') {
            $target_filesystem_type = $this->filesystem_type;
        }

        return $this->moveOrCopy(
            $target_directory,
            $target_name,
            $replace,
            $target_filesystem_type,
            'move'
        );
    }

    /**
     * Copies the file identified in $path to the $target_directory for the $target_filesystem_type adapter
     *  replacing existing contents and creating directories needed, if indicated
     *
     * Note: $target_filesystem_type is an instance of the Filesystem
     *
     * @param   string  $target_directory
     * @param   string  $target_name
     * @param   bool    $replace
     * @param   string  $target_filesystem_type
     * @param   string  $move_or_copy
     *
     * @return  null|void
     * @since   1.0
     * @throws  FileException
     */
    public function moveOrCopy
    (
        $target_directory,
        $target_name = '',
        $replace = false,
        $target_filesystem_type,
        $move_or_copy = 'copy'
    ) {
        /** Defaults */
        if ($target_directory == '') {
            $target_directory = $this->parent;
        }

        if ($target_name == '' && $this->is_file) {
            if ($target_directory == $this->parent) {
                throw new FileException
                ('Local Filesystem ' . $move_or_copy
                    . ': Must specify new file name when using the same target path: ' . $this->path);
            }
            $target_name = $this->name;
        }

        if ($this->is_file === true) {
            $base_folder = $this->parent;
        } else {
            $base_folder = $this->path;
        }

        /** Edits */
        if (file_exists($this->path)) {
        } else {
            throw new FileException
            ('Local Filesystem moveOrCopy: failed. This path does not exist: '
                . $this->path . ' Specified as source for ' . $move_or_copy
                . ' operation to ' . $target_directory);
        }

        if (file_exists($target_directory)) {
        } else {
            throw new FileException
            ('Local Filesystem moveOrCopy: failed. This path does not exist: '
                . $this->path . ' Specified as destination for ' . $move_or_copy
                . ' to ' . $target_directory);
        }

        if (is_writeable($target_directory) === false) {
            throw new FileException
            ('Local Filesystem Delete: No write access to file/path: ' . $target_directory);
        }

        if ($move_or_copy == 'move') {
            if (is_writeable($this->path) === false) {
                throw new FileException
                ('Local Filesystem Delete: No write access for moving source file/path: ' . $move_or_copy);
            }
        }

        $this->directories = array();
        $this->files       = array();

        /** Copy single file */
        if ($this->is_file === true) {
            $this->files[] = $this->path;

        } else {

            $this->discovery($this->path);

            if ($target_name == '') {

            } else {
                if (is_dir($target_directory . '/' . $target_name)) {

                } else {

                    $connect = new Adapter($target_filesystem_type, $target_directory, 'write',
                        $options = array('file' => $target_name)
                    );
                }
                $target_directory = $target_directory . '/' . $target_name;
                $target_name      = '';
            }
        }

        /** Create new target directories from source directories list */
        if (count($this->directories) > 0) {

            asort($this->directories);

            foreach ($this->directories as $directory) {

                if ($base_folder == $directory) {
                    $temp = $target_directory;
                } else {
                    $temp = $target_directory . substr($directory, strlen($base_folder), 99999);
                }

                if (is_dir($temp)) {

                } else {

                    $existing = dirname($temp);
                    $new_node = basename($temp);

                    $connect = new Adapter($target_filesystem_type, $existing, 'write',
                        $options = array('file' => $new_node)
                    );
                }
            }
        }

        /** Once all folders are in place, add files */
        if (count($this->files) > 0) {

            foreach ($this->files as $file) {

                /** Target Folder */
                $source_directory = dirname($file);

                if ($base_folder == $source_directory) {
                    $temp_directory = $target_directory;
                } else {
                    $temp_directory = $target_directory . substr($source_directory, strlen($base_folder), 99999);
                }

                /** Target File */
                if ($this->is_file === true) {
                    $temp_name = $target_name;
                } else {
                    $temp_name = basename($file);
                }

                /** Source */
                $connect = new Adapter('Local', $file, 'read', $options = array());
                $data    = $connect->action_results;

                /** Write Target */
                $connect = new Adapter($target_filesystem_type, $temp_directory, 'write',
                    $options = array(
                        'file'    => $temp_name,
                        'replace' => $replace,
                        'data'    => $data,
                    )
                );
            }
        }

        /** For move, remove the files and folders just copied */
        if ($move_or_copy == 'move') {

            if (count($this->files) > 0) {
                foreach ($this->files as $file) {
                    unlink($file);
                }
            }

            if (count($this->directories) > 0) {
                arsort($this->directories);
                foreach ($this->directories as $directory) {
                    rmdir($directory);
                }
            }
        }

        return true;
    }

    /**
     * Discovery of folders and files for specified path
     *
     * @param   $path
     *
     * @since   1.0
     * @return  void
     */
    public function discovery($path)
    {
        if (is_file($path)) {
            $this->files[] = $path;
        }

        if (is_dir($path)) {
        } else {
            return;
        }

        $this->directories[] = $path;

        $objects = new RecursiveIteratorIterator (
            new RecursiveDirectoryIterator($path),
            RecursiveIteratorIterator::SELF_FIRST);

        foreach ($objects as $name => $object) {

            if (is_file($name)) {
                $this->files[] = $name;

            } elseif (is_dir($name)) {
                $this->directories[] = $name;
            }
        }

        return;
    }

    /**
     * Returns the contents of the files located at path directory
     *
     * @param   bool  $recursive
     *
     * @return  mixed;
     * @since   1.0
     */
    public function getList($recursive)
    {
        if (file_exists($this->path)) {
            return file_get_contents($this->path);
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
        if (in_array($mode, array('0600', '0644', '0755', '0750'))) {
        } else {

            throw new FileException
            ('Local Filesystem: chmod method. Mode not provided: ' . $mode);
        }

        try {
            chmod($this->path, $mode);

        } catch (Exception $e) {

            throw new FileException
            ('Local Filesystem: chmod method failed for ' . $mode);
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
        if ($time == '' || $time === null || $time == 0) {
            $time = getDateTime($time);
        }

        try {

            if (touch($this->path, $time)) {
                echo $atime . ' modification time has been changed to present time';

            } else {
                echo 'Sorry, could not change modification time of ' . $this->path;
            }

        } catch (Exception $e) {

            throw new FileException
            ('Local Filesystem: is_readable method failed for ' . $this->path);
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
    private function getDateTime($time)
    {
        if ($time instanceof \DateTime) {
            return $time;
        }

        if (is_int($time) || is_float($time)) {
            return new DateTime('@' . intval($time));
        }

        return new DateTime($time);
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
