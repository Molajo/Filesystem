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

use DateTime;
use DateTimeZone;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

use Exception;
use RuntimeException;

use Molajo\Filesystem\Adapter as fsAdapter;

use Molajo\Filesystem\Adapter\FilesystemInterface;
use Molajo\Filesystem\Adapter\FilesystemActionsInterface;
use Molajo\Filesystem\Adapter\MetadataInterface;
use Molajo\Filesystem\Adapter\SystemInterface;

use Molajo\Filesystem\Exception\FilesystemException;
use Molajo\Filesystem\Exception\NotFoundException;

/**
 * Local Adapter for Filesystem
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class Local extends FilesystemProperties
    implements Filesysteminterface, FilesystemActionsInterface, MetadataInterface, SystemInterface
{
    /**
     * Class constructor
     *
     * @since   1.0
     * @throws  FilesystemException
     */
    public function __construct()
    {
        /** minimize memory http://php.net/manual/en/function.debug-backtrace.php */
        if (phpversion() < 50306) {
            $trace = debug_backtrace(1); // does not return objects
        }
        if (phpversion() > 50305) {
            $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT | DEBUG_BACKTRACE_IGNORE_ARGS);
        }
        if (phpversion() > 50399) {
            $trace = debug_backtrace(1, 1); // limit objects and arguments retrieved
        }

        if (isset($trace[1])) {
            if ($trace[1]['class'] == 'Molajo\\Filesystem\\Adapter'
                && $trace[1]['function'] == 'getFilesystemType'
            ) {
                $this->filesystem_type = 'Local';
                return $this;
            }
        }

        throw new FilesystemException
        ('Direct access only allowed via the File System Adapter Class-getFilesystemType method.');
    }

    /**
     *  FilesystemInterface
     */

    /**
     * Method to connect to a Local server
     *
     * @param   string  $type
     * @param   array   $options
     *
     * @return  void
     * @since   1.0
     */
    public function connect($options = array())
    {
        $this->setOptions($options);
        $this->setRoot();
        $this->setPersistence();
        $this->setDirectoryDefaultPermissions();
        $this->setFileDefaultPermissions();
        $this->setReadOnly();

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
        $this->path = $path;

        return $this->path;
    }

    /**
     * Retrieves and sets metadata for the file specified in path
     *
     * @return  void
     * @since   1.0
     */
    public function getMetadata()
    {
        $this->exists();
        $this->getAbsolutePath();
        $this->isAbsolutePath();
        $this->isDirectory();
        $this->isFile();
        $this->isLink();
        $this->getType();
        $this->getName();
        $this->getParent();
        $this->getExtension();

        $this->discovery($this->path); // must follow the is_file, etc., series

        $this->getSize();
        $this->getMimeType();
        $this->getOwner();
        $this->getGroup();
        $this->getCreateDate();
        $this->getAccessDate();
        $this->getModifiedDate();
        $this->isReadable();
        $this->isWriteable();
        $this->isExecutable();

        return;
    }

    /**
     * Execute the action requested
     *
     * @param   string  $action
     *
     * @return  void
     * @since   1.0
     * @throws  FilesystemException
     */
    public function doAction($action = '')
    {
        $action = strtolower($action);

        switch ($action) {
            case 'read':
                $this->data = $this->read();
                break;

            case 'write':
                if (isset($options['file'])) {
                    $file = $options['file'];
                } else {
                    throw new \BadMethodCallException
                    ('Filesystem Adapter: Must provide filename for write request. Path: ' . $this->path);
                }

                $replace = true;
                if (isset($options['replace'])) {
                    $replace = $options['replace'];
                }

                if (isset($options['data'])) {
                    $data = $options['data'];
                } else {
                    $data = '';
                }

                $this->data = $this->fsType->write($file, $replace, $data);

                break;

            case 'delete':

                $delete_subdirectories = true;

                if (isset($options['delete_subdirectories'])) {
                    $delete_subdirectories = (int)$options['delete_subdirectories'];
                }

                $delete_subdirectories = $this->fsType->setTorF($delete_subdirectories, true);

                $this->data = $this->delete($delete_subdirectories);

                $this->data = $this->fsType->delete($delete_subdirectories);

                break;

            case 'copy':
            case 'move':

                if (isset($this->options['target_directory'])) {
                    $target_directory = $this->options['target_directory'];

                } else {
                    throw new \BadMethodCallException
                    ('Filesystem Adapter: Must provide target_directory for copy request. Path: ' . $this->path);
                }

                if (isset($this->options['target_name'])) {
                    $target_name = $this->options['target_name'];

                } else {
                    $target_name = '';
                }

                $replace = true;

                if (isset($this->options['replace'])) {
                    $replace = (int)$this->options['replace'];
                }

                $replace = $this->fsType->setTorF($replace, true);

                if (isset($this->options['target_filesystem_type'])) {
                    $target_filesystem_type = $this->options['target_filesystem_type'];
                } else {
                    $target_filesystem_type = $this->fsType->filesystem_type;
                }

                $this->data
                    = $this->$action($target_directory, $target_name, $replace, $target_filesystem_type);

                break;

            case 'setRelativePath':

                if (isset($this->options['target_directory'])) {
                    $target_directory = $this->options['target_directory'];

                } else {
                    throw new \BadMethodCallException
                    ('Filesystem Adapter: Must provide target_directory for copy request. Path: ' . $this->path);
                }

                break;

            case 'chmod':

                $recursive = true;

                if (isset($this->options['recursive'])) {
                    $recursive = (int)$this->options['recursive'];
                }

                $recursive = $this->fsType->setTorF($recursive, true);

                $this->data = $this->getList($recursive);

                $this->fsType->chmod($mode);


                $this->data = $this->fsType->chmod($mode);

                return $this->data;

                break;

            case 'touch':

                $time = null;

                if (isset($this->options['time'])) {
                    $time = (int)$this->options['time'];
                }

                $atime = null;

                if (isset($this->options['atime'])) {
                    $atime = (int)$this->options['atime'];
                }

                $this->data = $this->fsType->touch($time, $atime);

                return $this->data;

                break;
        }

        throw new FilesystemException
        ('Filesystem Adapter Method ' . $action . ' does not exist.');

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

    /**
     *  FilesystemActionsInterface
     */

    /**
     * Returns the contents of the file identified by path
     *
     * @return  mixed
     * @since   1.0
     * @throws  NotFoundException when file does not exist
     */
    public function read()
    {
        if ($this->exists === false) {
            throw new NotFoundException
            ('Filesystem Local Read: File does not exist: ' . $this->path);
        }

        if ($this->is_file === false) {
            throw new NotFoundException
            ('Filesystem Local Read: Is not a file: ' . $this->path);
        }

        if ($this->is_readable === false) {
            throw new NotFoundException
            ('Filesystem Local Read: No permission, not readable: ' . $this->path);
        }

        try {
            $this->data = file_get_contents($this->path);

        } catch (\Exception $e) {

            throw new NotFoundException
            ('Filesystem Local Read: Error reading file: ' . $this->path);
        }

        if ($this->data === false) {
            ('Filesystem Local Read: Empty File: ' . $this->path);
        }

        return $this->data;
    }

    /**
     * Returns the contents of the files located at path directory
     *
     * @param   bool  $recursive
     *
     * @return  array
     * @since   1.0
     */
    public function getList($recursive = false)
    {
        if (isset($options['recursive'])) {
            $recursive = $options['recursive'];
        }
        $this->data = $this->fsType->getList($recursive);

        return $this->data;

        if (is_file($this->path)) {
            return $this->read();
        }

        $files = array();

        $this->discovery();

        foreach ($this->directories as $directory) {
            $files[] = $directory;
        }
        foreach ($this->files as $file) {
            $files[] = $file;
        }

        asort($files);
        $this->data = $files;

        return;
    }

    /**
     * Creates or replaces the file or directory identified in path using the data value
     *
     * @param   string  $file       spaces for create directory
     * @param   bool    $replace
     * @param   string  $data       spaces for create directory
     *
     * @return  void
     * @since   1.0
     */
    public function write($file = '', $replace = true, $data = '')
    {
        if ($this->exists === false) {

        } elseif ($this->is_file === true || $this->is_directory === true) {

        } else {
            throw new FilesystemException
            ('Filesystem Local Write: must be directory or file: ' . $this->path . '/' . $file);
        }

        if (trim($data) == '' || strlen($data) == 0) {

            if ($file == '') {
                throw new FilesystemException
                ('Local Filesystem: attempting to write no data to file: ' . $this->path . '/' . $file);

            } else {
                $this->createDirectory($this->path . '/' . $file);

                return;
            }
        }

        if (file_exists($this->path)) {
        } else {
            $this->createDirectory($this->path);
        }

        if (file_exists($this->path . '/' . $file)) {

            if ($replace === false) {
                throw new FilesystemException
                ('Local Filesystem: attempting to write to existing file: ' . $this->path . '/' . $file);
            }

            \unlink($this->path . '/' . $file);
        }

        if ($this->isWriteable($this->path . '/' . $file) === false) {
            throw new FilesystemException
            ('Local Filesystem: file is not writable: ' . $this->path . '/' . $file);
        }


        try {
            \file_put_contents($this->path . '/' . $file, $data);

        } catch (Exception $e) {

            throw new NotFoundException
            ('Local Filesystem: error writing file ' . $this->path . '/' . $file);
        }

        if (file_exists($this->path . '/' . $file) === false) {
            throw new NotFoundException
            ('Filesystem Write: error writing to file: ' . $this->path . '/' . $file);
        }

        return;
    }

    /**
     * Create Directory
     *
     * @param   bool  $path
     *
     * @return  void
     * @since   1.0
     * @throws  FilesystemException
     */
    protected function createDirectory($path)
    {
        if (file_exists($path)) {
            return;
        }

        try {
            \mkdir($path, $this->default_directory_permissions, true);

        } catch (Exception $e) {

            throw new FilesystemException
            ('Filesystem Create Directory: error creating directory: ' . $path);
        }

        return;
    }

    /**
     * Deletes the file identified in path. Empty directories are removed if so indicated
     *
     * @param   bool  $delete_subdirectories  default true
     *
     * @return  void
     * @since   1.0
     * @throws  FilesystemException
     * @throws  NotFoundException
     */
    public function delete($delete_subdirectories = true)
    {
        if (file_exists($this->path)) {
        } else {
            throw new NotFoundException
            ('Local Filesystem Delete: File not found ' . $this->path);
        }

        if ($this->is_writable === false) {
            throw new FilesystemException
            ('Local Filesystem Delete: No write access to file/path: ' . $this->path);
        }

        try {

            if (file_exists($this->path)) {
            } else {
                return true;
            }

            $this->_deleteDiscoveryFilesArray();

            $this->_deleteDiscoveryDirectoriesArray();

        } catch (Exception $e) {

            throw new FilesystemException
            ('Local Filesystem Delete: failed for: ' . $this->path);
        }

        return;
    }

    /**
     * Common code for processing the discovery File Array
     *
     * @return  void
     * @since   1.0
     */
    private function _deleteDiscoveryFilesArray()
    {
        if (count($this->files) > 0) {
            foreach ($this->files as $file) {
                unlink($file);
            }
        }

        return;
    }

    /**
     * Common code for processing the discovery File Array
     *
     * @return  void
     * @since   1.0
     */
    private function _deleteDiscoveryDirectoriesArray()
    {
        if (count($this->directories) > 0) {
            arsort($this->directories);
            foreach ($this->directories as $directory) {
                rmdir($directory);
            }
        }

        return;
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
     * @return  void
     * @since   1.0
     */
    public function copy($target_directory, $target_name = '', $replace = true, $target_filesystem_type = '')
    {
        if ($target_filesystem_type == '') {
            $target_filesystem_type = $this->filesystem_type;
        }

        $this->moveOrCopy($target_directory, $target_name, $replace, $target_filesystem_type, 'copy');

        return;
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
    public function move($target_directory, $target_name = '', $replace = true, $target_filesystem_type = '')
    {
        if ($target_filesystem_type == '') {
            $target_filesystem_type = $this->filesystem_type;
        }

        $this->moveOrCopy($target_directory, $target_name, $replace, $target_filesystem_type, 'move');

        return;
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
     * @throws  FilesystemException
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
                throw new FilesystemException
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
            throw new FilesystemException
            ('Local Filesystem moveOrCopy: failed. This path does not exist: '
                . $this->path . ' Specified as source for ' . $move_or_copy
                . ' operation to ' . $target_directory);
        }

        if (file_exists($target_directory)) {
        } else {
            throw new FilesystemException
            ('Local Filesystem moveOrCopy: failed. This path does not exist: '
                . $this->path . ' Specified as destination for ' . $move_or_copy
                . ' to ' . $target_directory);
        }

        if (is_writeable($target_directory) === false) {
            throw new FilesystemException
            ('Local Filesystem Delete: No write access to file/path: ' . $target_directory);
        }

        if ($move_or_copy == 'move') {
            if (is_writeable($this->path) === false) {
                throw new FilesystemException
                ('Local Filesystem Delete: No write access for moving source file/path: ' . $move_or_copy);
            }
        }

        $this->discovery();

        if ($this->is_file === true || $target_name == '') {

        } else {
            if (is_dir($target_directory . '/' . $target_name)) {

            } else {

                new fsAdapter('write', $target_directory, $target_filesystem_type,
                    $options = array('file' => $target_name)
                );
            }
            $target_directory = $target_directory . '/' . $target_name;
            $target_name      = '';
        }

        /** Create new target directories from source directories list */
        if (count($this->directories) > 0) {

            asort($this->directories);

            $parent   = '';
            $new_node = '';
            $new_path = '';

            foreach ($this->directories as $directory) {

                $new_path = $this->build_new_path($directory, $target_directory, $base_folder);

                if (is_dir($new_path)) {

                } else {

                    $parent   = dirname($new_path);
                    $new_node = basename($new_path);

                    new fsAdapter('write', $parent, $target_filesystem_type,
                        $options = array('file' => $new_node)
                    );
                }

                $parent   = '';
                $new_node = '';
                $new_path = '';
            }
        }

        /** Copy files now that directories are in place */
        if (count($this->files) > 0) {

            $path_name = '';
            $file_name = '';

            foreach ($this->files as $file) {

                $new_path = $this->build_new_path($file, $target_directory, $base_folder);

                /** Single file copy or move */
                if ($this->is_file === true) {
                    $file_name = $target_name;
                } else {
                    $file_name = basename($file);
                }

                /** Source */
                $connect = new fsAdapter('Read', $file, 'Local', $options = array());
                $data    = $connect->data;

                /** Write Target */
                new fsAdapter('Write', $new_path, $target_filesystem_type,
                    $options = array(
                        'file'    => $file_name,
                        'replace' => $replace,
                        'data'    => $data,
                    )
                );

                $path_name = '';
                $file_name = '';
            }
        }

        /** For move, remove the files and folders just copied */
        if ($move_or_copy == 'move') {
            $this->_deleteDiscoveryFilesArray();
            $this->_deleteDiscoveryDirectoriesArray();
        }

        return true;
    }

    /**
     * Convert the path into a path relative to the path passed in
     *
     * @param  string  $absolute_path_of_target
     *
     * @return  void
     * @throws  FilesystemException
     * @since   1.0
     */
    public function setRelativePath($absolute_path_of_target = '')
    {
        // Normalize separators on windows
        if (defined('PHP_WINDOWS_VERSION_MAJOR')) {
            $endPath   = strtr($endPath, '\\', '/');
            $startPath = strtr($startPath, '\\', '/');
        }

        // Split the paths into arrays
        $startPathArr = explode('/', trim($startPath, '/'));
        $endPathArr   = explode('/', trim($endPath, '/'));

        // Find for which directory the common path stops
        $index = 0;
        while (isset($startPathArr[$index]) && isset($endPathArr[$index]) && $startPathArr[$index] === $endPathArr[$index]) {
            $index ++;
        }

        // Determine how deep the start path is relative to the common path (ie, "web/bundles" = 2 levels)
        $depth = count($startPathArr) - $index;

        // Repeated "../" for each level need to reach the common path
        $traverser = str_repeat('../', $depth);

        $endPathRemainder = implode('/', array_slice($endPathArr, $index));

        // Construct $endPath from traversing to the common path, then to the remaining $endPath
        $relativePath = $traverser . (strlen($endPathRemainder) > 0 ? $endPathRemainder . '/' : '');

        $save = (strlen($relativePath) === 0) ? './' : $relativePath;

        $this->relative_path = $save;

        return;
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
     * @return  void
     * @throws  FilesystemException
     * @since   1.0
     */
    public function chmod($mode = 0)
    {
        if (in_array($mode, array('0600', '0644', '0755', '0750'))) {
        } else {

            throw new FilesystemException
            ('Local Filesystem: chmod method. Mode not provided: ' . $mode);
        }

        try {
            chmod($this->path, $mode);

        } catch (Exception $e) {

            throw new FilesystemException
            ('Local Filesystem: chmod method failed for ' . $mode);
        }

        return;
    }

    /**
     * Update the touch time and/or the access time for the directory or file identified in the path
     *
     * @param   int     $time
     * @param   int     $atime
     *
     * @return  void
     * @throws  FilesystemException
     * @since   1.0
     */
    public function touch($time = null, $atime = null)
    {
        if ($time == '' || $time === null || $time == 0) {
            $time = $this->getDateTime($time);
        }

        try {

            if (touch($this->path, $time)) {
                echo $atime . ' modification time has been changed to present time';

            } else {
                echo 'Sorry, could not change modification time of ' . $this->path;
            }

        } catch (Exception $e) {

            throw new FilesystemException
            ('Local Filesystem: is_readable method failed for ' . $this->path);
        }

        return;
    }

    /**
     *  Path
     */

    /**
     * Get the Path
     *
     * @since   1.0
     * @return  string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Does the path exist (as either a file or a folder)?
     *
     * @return void
     */
    public function exists()
    {
        $this->exists = false;
        if (file_exists($this->path)) {
            $this->exists = true;
        }

        return;
    }

    /**
     * Retrieves the absolute path, which is the relative path from the root directory,
     *  prepended with a '/'.
     *
     * @return  void
     * @since   1.0
     * @throws  NotFoundException
     */
    public function getAbsolutePath()
    {
        if ($this->exists === false) {
            $this->absolute_path = null;
            return;
        }

        $this->absolute_path = realpath($this->path);

        return;
    }

    /**
     * Indicates whether the given path is absolute or not
     *
     * Relative path - describes how to get from a particular directory to a file or directory
     * Absolute Path - relative path from the root directory, prepended with a '/'.
     *
     * @return  void
     * @since   1.0
     */
    public function isAbsolutePath()
    {
        if ($this->exists === false) {
            $this->is_absolute_path = null;
            return;
        }

        if (substr($this->path, 0, 1) == '/') {
            $this->is_absolute_path = true;
        } else {
            $this->is_absolute_path = false;
        }

        return;
    }

    /**
     * Get File or Directory Name
     *
     * @return  void
     * @since   1.0
     * @throws  NotFoundException
     */
    public function getName()
    {
        if ($this->exists === false) {
            $this->name = null;
            return;
        }

        $this->name = basename($this->path);

        return;
    }

    /**
     * Returns true or false indicator as to whether or not the path is a directory
     *
     * @return  void
     * @since   1.0
     */
    public function isDirectory()
    {
        $this->is_directory = false;

        if (is_dir($this->path)) {
            $this->is_directory = true;
        }

        return;
    }

    /**
     * Returns true or false indicator as to whether or not the path is a file
     *
     * @return  void
     * @since   1.0
     */
    public function isFile()
    {
        $this->is_file = false;

        if (is_file($this->path)) {
            $this->is_file = true;
        }

        return;
    }

    /**
     * Returns true or false indicator as to whether or not the path is a link
     *
     * @return  void
     * @since   1.0
     */
    public function isLink()
    {
        $this->is_link = false;

        if (is_link($this->path)) {
            $this->is_link = true;
        }

        return;
    }

    /**
     * Returns the value 'directory, 'file' or 'link' for the type determined
     *  from the path
     *
     * @return  void
     * @since   1.0
     * @throws  FilesystemException
     * @throws  NotFoundException
     */
    public function getType()
    {
        if ($this->exists === false) {
            $this->type = null;
            return;
        }

        if ($this->is_directory === true) {
            $this->type = 'directory';
            return;
        }

        if ($this->is_file === true) {
            $this->type = 'file';
            return;
        }

        if ($this->is_link === true) {
            $this->type = 'link';
            return;
        }

        throw new FilesystemException ('Not a directory, file or a link.');
    }

    /**
     * Get Parent
     *
     * @return  void
     * @since   1.0
     * @throws  NotFoundException
     */
    public function getParent()
    {
        if ($this->exists === false) {
            $this->parent = null;
            return;
        }

        $this->parent = dirname($this->path);

        return;
    }

    /**
     * Get File Extension
     *
     * @return  void
     * @since   1.0
     * @throws  NotFoundException
     */
    public function getExtension()
    {
        if ($this->exists === false) {
            $this->extension = null;
            return;
        }

        if ($this->is_file === true) {

        } elseif ($this->is_directory === true) {
            $this->extension = '';
            return;

        } else {
            throw new NotFoundException
            ('Filesystem Local: not a valid file. Path: ' . $this->path);
        }

        $this->extension = pathinfo(basename($this->path), PATHINFO_EXTENSION);

        return;
    }

    /**
     * Get the file size of a given file.
     *
     * $param  bool  $recursive  For directory, recursively calculate file calculations default true
     *
     * @return  void
     * @since   1.0
     */
    public function getSize($recursive = true)
    {
        $this->size = 0;

        if (count($this->files) > 0) {

            foreach ($this->files as $file) {
                $this->size = $this->size + filesize($file);
            }
        }

        return;
    }

    /**
     * Returns the mime type of the file located at path directory
     *
     * @return  void
     * @since   1.0
     */
    public function getMimeType()
    {
        $this->mime_type = null;

        if ($this->exists === false) {
            return;
        }

        if ($this->is_file === true) {
        } else {
            return;
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

        return;
    }

    /**
     * Returns the owner of the file or directory defined in the path
     *
     * @return  void
     * @since   1.0
     */
    public function getOwner()
    {
        $this->owner = null;

        if ($this->exists === true) {
        } else {
            return;
        }

        $this->owner = fileowner($this->path);

        return;
    }

    /**
     * Returns the group for the file or directory defined in the path
     *
     * @return  void
     * @since   1.0
     */
    public function getGroup()
    {
        $this->group = null;

        if ($this->exists === true) {
        } else {
            return;
        }

        $this->group = filegroup($this->path);

        return;
    }

    /**
     * Retrieves Create Date for directory or file identified in the path
     *
     * @return  void
     * @since   1.0
     * @throws  FilesystemException
     */
    public function getCreateDate()
    {
        $this->create_date = null;

        if ($this->exists === true) {
        } else {
            return;
        }

        try {
            $this->create_date = date("Y-m-d H:i:s", filectime($this->path));

        } catch (Exception $e) {

            throw new FilesystemException
            ('Local Filesystem getCreateDate failed for ' . $this->path);
        }

        return;
    }

    /**
     * Retrieves Last Access Date for directory or file identified in the path
     *
     * @return  void
     * @since   1.0
     * @throws  FilesystemException
     */
    public function getAccessDate()
    {
        $this->access_date = null;

        if ($this->exists === true) {
        } else {
            return;
        }

        try {

            $this->access_date = date("Y-m-d H:i:s", fileatime($this->path));

        } catch (Exception $e) {

            throw new FilesystemException
            ('Local Filesystem getAccessDate failed for ' . $this->path);
        }

        return;
    }

    /**
     * Retrieves Last Update Date for directory or file identified in the path
     *
     * @return  void
     * @since   1.0
     * @throws  FilesystemException
     */
    public function getModifiedDate()
    {
        $this->modified_date = null;

        if ($this->exists === true) {
        } else {
            return;
        }

        try {

            $this->modified_date = date("Y-m-d H:i:s", filemtime($this->path));

        } catch (Exception $e) {
            throw new FilesystemException

            ('Local Filesystem: getModifiedDate method failed for ' . $this->path);
        }

        return;
    }

    /**
     * Tests if the group specified: 'owner', 'group', or 'world' has read access
     *  Returns true or false
     *
     * @return  void
     * @since   1.0
     */
    public function isReadable()
    {
        $this->is_readable = null;

        if ($this->exists === true) {
        } else {
            return;
        }

        $this->is_readable = is_readable($this->path);

        return;
    }

    /**
     * Tests if the group specified: 'owner', 'group', or 'world' has write access
     *  Returns true or false
     *
     * @return  void
     * @since   1.0
     */
    public function isWriteable()
    {
        $this->is_writable = null;

        if ($this->exists === true) {
        } else {
            return;
        }

        $this->is_writable = is_writable($this->path);

        return;
    }

    /**
     * Tests if the group specified: 'owner', 'group', or 'world' has execute access
     *  Returns true or false
     *
     * @return  void
     * @since   1.0
     */
    public function isExecutable()
    {
        $this->is_executable = null;

        if ($this->exists === true) {
        } else {
            return;
        }

        $this->is_executable = is_executable($this->path);

        return;
    }
}
