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
    implements FilesystemInterface, FilesystemActionsInterface, MetadataInterface, SystemInterface
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
        $this->isRoot();
        $this->isDirectory();
        $this->isFile();
        $this->isLink();
        $this->getType();
        $this->getName();
        $this->getParent();
        $this->getExtension();
        $this->getNoextension();
        $this->getMimeType();
        $this->getOwner();
        $this->getGroup();
        $this->getCreateDate();
        $this->getAccessDate();
        $this->getModifiedDate();
        $this->isReadable();
        $this->isWriteable();
        $this->isExecutable();
        $this->hashFileMd5();
        $this->hashFileSha1();

        /**
         *  Discovery creates an array of Files and Directories based on Path
         */
        if ($this->exists === true) {
            $this->discovery($this->path);
        }

        $this->getSize();

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
            case 'metadata':
                // simply return with what is already available
                break;

            case 'read':
                $this->read();
                break;

            case 'write':
                $file = '';
                if (isset($this->options['file'])) {
                    $file = $this->options['file'];
                }

                $data = '';
                if (isset($this->options['data'])) {
                    $data = $this->options['data'];
                }

                $replace = false;
                if (isset($this->options['replace'])) {
                    $replace = $this->options['replace'];
                }
                $replace = $this->setTorF($replace, true);

                $append = false;
                if (isset($this->options['append'])) {
                    $append = $this->options['append'];
                }
                $append = $this->setTorF($append, true);

                $truncate = false;
                if (isset($this->options['truncate'])) {
                    $truncate = $this->options['truncate'];
                }
                $truncate = $this->setTorF($truncate, true);

                $this->write($file, $data, $replace, $append, $truncate);

                break;

            case 'getlist':

                $recursive = false;
                if (isset($this->options['recursive'])) {
                    $recursive = $this->options['recursive'];
                }
                $recursive = $this->setTorF($recursive, false);

                $exclude_files = false;
                if (isset($this->options['exclude_files'])) {
                    $exclude_files = $this->options['exclude_files'];
                }
                $exclude_files = $this->setTorF($exclude_files, false);

                $exclude_folders = false;
                if (isset($this->options['exclude_folders'])) {
                    $exclude_folders = $this->options['exclude_folders'];
                }
                $exclude_folders = $this->setTorF($exclude_folders, false);

                $extension_list = array();
                if (isset($this->options['extension_list'])) {
                    $extension_list = $this->options['extension_list'];
                }
                $extension_list = $this->setTorF($extension_list, array());

                $name_mask = null;
                if (isset($this->options['name_mask'])) {
                    $name_mask = $this->options['name_mask'];
                }
                if ($name_mask == '' || trim($name_mask)) {
                    $name_mask = null;
                }

                $this->getList($recursive, $exclude_files, $exclude_folders, $extension_list, $name_mask);

                break;

            case 'delete':

                $delete_subdirectories = true;

                if (isset($this->options['delete_subdirectories'])) {
                    $delete_subdirectories = (int)$this->options['delete_subdirectories'];
                }

                $delete_subdirectories = $this->setTorF($delete_subdirectories, true);

                $this->delete($delete_subdirectories);

                break;

            case 'copy':
            case 'move':

                if (isset($this->options['target_directory'])) {
                    $target_directory = $this->options['target_directory'];

                } else {
                    throw new \BadMethodCallException
                    ('Local Filesystem: MTarget_directory for Copy Action. Path: ' . $this->path);
                }

                if (isset($this->options['target_name'])) {
                    $target_name = $this->options['target_name'];

                } else {
                    $target_name = '';
                }

                $replace = true;

                if (isset($this->options['replace'])) {
                    $replace = $this->options['replace'];
                }

                $replace = $this->setTorF($replace, true);

                if (isset($this->options['target_filesystem_type'])) {
                    $target_filesystem_type = $this->options['target_filesystem_type'];
                } else {
                    $target_filesystem_type = $this->filesystem_type;
                }

                $this->data
                    = $this->$action($target_directory, $target_name, $replace, $target_filesystem_type);

                break;

            case 'getrelativepath':

                if (isset($this->options['relative_to_this_path'])) {
                    $relative_to_this_path = $this->options['relative_to_this_path'];

                } else {
                    throw new \BadMethodCallException
                    ('Local Filesystem: Must provide relative_to_this_path for relative_path request. Path: '
                        . $this->path);
                }

                $this->getRelativePath($relative_to_this_path);

                break;

            case 'chmod':

                $mode = '';

                if (isset($this->options['mode'])) {
                    $mode = (int)$this->options['mode'];
                }

                $this->chmod($mode);

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

                $this->touch($time, $atime);

                break;

            default:
                throw new NotFoundException
                ($this->filesystem_type . ' Filesystem doAction Method ' . $action . ' does not exist.');
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
            ('Local Filesystem Read: File does not exist: ' . $this->path);
        }

        if ($this->is_file === false) {
            throw new NotFoundException
            ('Local Filesystem Read: Is not a file: ' . $this->path);
        }

        if ($this->is_readable === false) {
            throw new NotFoundException
            ('Local Filesystem Read: Not permitted to read: ' . $this->path);
        }

        try {
            $this->data = file_get_contents($this->path);

        } catch (\Exception $e) {

            throw new NotFoundException
            ('Local Filesystem Read: Error reading file: ' . $this->path);
        }

        if ($this->data === false) {
            ('Local Filesystem Read: Empty File: ' . $this->path);
        }

        return;
    }

    /**
     * Returns the contents of the files located at path directory
     *
     * @param   bool    $recursive
     * @param   bool    $exclude_files
     * @param   bool    $exclude_folders
     * @param   array   $extension_list
     * @param   string  $name_mask
     *
     * @return  array
     * @since   1.0
     */
    public function getList(
        $recursive = false,
        $exclude_files = false,
        $exclude_folders = false,
        $extension_list = array(),
        $name_mask = null
    ) {
        if (is_file($this->path)) {
            return $this->read();
        }

        if ($exclude_folders === true) {
        } else {
            foreach ($this->directories as $directory) {

                if ($recursive === false) {
                    if ($this->path == $directory) {
                        $files[] = $directory;
                    }
                } else {
                    $files[] = $directory;
                }
            }
        }

        if ($exclude_files === true) {
        } else {
            foreach ($this->files as $file) {

                if ($recursive === false) {
                    if ($this->path == pathinfo($file, PATHINFO_DIRNAME)) {
                        $files[] = $file;
                    }

                } else {
                    $files[] = $file;
                }
            }
        }

        if (count($files) > 0) {
            asort($files);
        }

        $this->data = $files;

        return;
    }

    /**
     * For a file request, creates, appends to, replaces or truncates the file identified in path
     * For a folder request, create is the only valid option
     *
     * @param   string  $file       Will be empty when the write action is to create directory
     * @param   string  $data       Will be empty when the write action is to create directory
     * @param   bool    $replace    Default false
     * @param   bool    $append     Default false
     * @param   bool    $truncate   Default false
     *
     * @return  void
     * @since   1.0
     * @throws  FilesystemException
     * @throws  NotFoundException
     */
    public function write($file = '', $data = '', $replace = false, $append = false, $truncate = false)
    {
        if ($append === true) {
            $this->append($file, $data, $replace, $append, $truncate);
            return;
        }

        if ($truncate === true) {
            $this->truncate($file, $data, $replace, $append, $truncate);
            return;
        }

        if ($this->exists === false) {

        } elseif ($this->is_file === true || $this->is_directory === true) {

        } else {
            throw new FilesystemException
            ('Local Filesystem Write: must be directory or file: ' . $this->path . '/' . $file);
        }

        if (trim($data) == '' || strlen($data) == 0) {
            if ($this->is_file === true) {

                throw new FilesystemException
                ('Local Filesystem: attempting to write no data to file: ' . $this->path . '/' . $file);
            }
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

        if ($this->isWriteable($this->path . '/' . $file) === false) {
            throw new FilesystemException
            ('Local Filesystem: file is not writable: ' . $this->path . '/' . $file);
        }

        if (file_exists($this->path . '/' . $file)) {

            if ($replace === false) {
                throw new FilesystemException
                ('Local Filesystem: attempting to write to existing file: ' . $this->path . '/' . $file);
            }

            \unlink($this->path . '/' . $file);
        }

        try {
            \file_put_contents($this->path . '/' . $file, $data);

        } catch (Exception $e) {

            throw new NotFoundException
            ('Local Filesystem: error writing file ' . $this->path . '/' . $file);
        }

        return;
    }

    /**
     * Append data to file identified in path using the data value
     *
     * @param   string  $file       Will be empty when the write action is to create directory
     * @param   string  $data       Will be empty when the write action is to create directory
     * @param   bool    $replace    Default false
     * @param   bool    $append     Default false
     * @param   bool    $truncate   Default false
     *
     * @return  void
     * @since   1.0
     */
    private function append($file = '', $data = '', $replace = false, $append = false, $truncate = false)
    {
        if ($this->exists === true) {
        } elseif ($this->is_file === false) {
        } else {
            throw new FilesystemException
            ('Local Filesystem: attempting to append to a folder, not a file ' . $this->path);
        }

        if ($replace === true || $truncate === true) {
            throw new FilesystemException
            ('Local Filesystem Write: replace and truncate must both be false for append action: ' . $this->path);
        }

        try {
            \file_put_contents($this->path, $data, FILE_APPEND);

        } catch (Exception $e) {

            throw new NotFoundException
            ('Local Filesystem: error appending to file ' . $this->path);
        }

        return;
    }

    /**
     * Truncate file identified in path using the data value
     *
     * @param   string  $file       Will be empty when the write action is to create directory
     * @param   string  $data       Will be empty when the write action is to create directory
     * @param   bool    $replace    Default false
     * @param   bool    $append     Default false
     * @param   bool    $truncate   Default false
     *
     * @return  void
     * @since   1.0
     */
    private function truncate($file = '', $data = '', $replace = false, $append = false, $truncate = false)
    {
        if ($this->exists === false) {
            throw new FilesystemException
            ('Local Filesystem: attempting to truncate file that does not exist. ' . $this->path);
        }

        if ($this->is_file === true) {
        } else {
            throw new FilesystemException
            ('Local Filesystem: only a file can be truncated. ' . $this->path);
        }

        if ($replace === true || $append === true) {
            throw new FilesystemException
            ('Local Filesystem Write: replace and append must both be false for truncate action: ' . $this->path . '/' . $file);
        }

        if (trim($data) == '' || strlen($data) == 0) {
        } else {
            throw new FilesystemException
            ('Local Filesystem: data cannot be defined for truncate action. ' . $this->path);
        }

        if ($this->isWriteable($this->path) === false) {
            throw new FilesystemException
            ('Local Filesystem: file is not writable and cannot be truncated: ' . $this->path);
        }

        try {
            $fp = \fopen($this->path, "w");
            fclose($fp);

        } catch (Exception $e) {

            throw new NotFoundException
            ('Local Filesystem: error truncating file ' . $this->path);
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
            mkdir($path, $this->default_directory_permissions, true);

        } catch (Exception $e) {

            throw new FilesystemException
            ('Filesystem Create Directory: error creating directory: ' . $path);
        }

        return;
    }

    /**
     * Deletes the file identified in path.
     * Empty subdirectories are removed if $delete_subdirectories is true
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
        if ($this->is_root === true) {
            throw new NotFoundException
            ('Local Filesystem Delete: Request to delete root is not allowed' . $this->path);
        }

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
                return;
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

        if ($this->is_file === true || $target_name == '') {

        } else {
            if (is_dir($target_directory . '/' . $target_name)) {

            } else {

                new fsAdapter('write', $target_directory, $target_filesystem_type,
                    $this->options = array('file' => $target_name)
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
                        $this->options = array('file' => $new_node)
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
                $adapter = new fsAdapter('Read', $file);
                $data    = $adapter->fs->data;

                /** Write Target */
                new fsAdapter('Write', $new_path, $target_filesystem_type,
                    $this->options = array(
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
     * @param  string  $relative_to_this_path
     *
     * @return  void
     * @throws  FilesystemException
     * @since   1.0
     */
    public function getRelativePath($relative_to_this_path = '')
    {
        $hold   = getcwd();
        $target = $this->normalise($relative_to_this_path);
        chdir($target);
        $this->data = realpath($this->path);
        chdir($hold);

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
        if (in_array($mode, array(0600, 0644, 0755, 0750))) {
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
     * Is this the root folder?
     *
     * @return  void
     * @since   1.0
     * @throws  NotFoundException
     */
    public function isRoot()
    {
        $this->is_root = false;

        if ($this->path == $this->root) {
            $this->is_root = true;
        } else {
            if ($this->path == '/' || $this->path == '\\') {
                $this->is_root = true;
            }
        }

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

        if ($this->is_root === true) {
            $this->parent = null;
            return;
        }

        $this->parent = pathinfo($this->path, PATHINFO_DIRNAME);

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

        $this->name = pathinfo($this->path, PATHINFO_BASENAME);

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
            ('Local Filesystem: not a valid file. Path: ' . $this->path);
        }

        $this->extension = pathinfo($this->path, PATHINFO_EXTENSION);

        return;
    }

    /**
     * Get File without Extension
     *
     * @return  void
     * @since   1.0
     * @throws  NotFoundException
     */
    public function getNoExtension()
    {
        if ($this->exists === false) {
            $this->no_extension = null;
            return;
        }

        if ($this->is_file === true) {

        } elseif ($this->is_directory === true) {
            $this->no_extension = '';
            return;

        } else {
            throw new NotFoundException
            ('Local Filesystem: not a valid file. Path: ' . $this->path);
        }

        $this->no_extension = pathinfo($this->path, PATHINFO_FILENAME);

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
            ('Local Filesystem: getMimeType either finfo_open or mime_content_type are required in PHP');
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
     * Tests if the current user has read access
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
     * Tests if the current user has write access
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
     * Tests if the current user has executable access
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

    /**
     * Calculates the md5 hash of a given file
     *
     * @return  void
     * @since   1.0
     */
    public function hashFileMd5()
    {
        $this->hash_file_md5 = null;

        if ($this->exists === true) {

        } elseif ($this->is_file === true) {

        } else {
            return;
        }

        $this->hash_file_md5 = md5_file($this->path);

        return;
    }

    /**
     * Hash file sha1
     * http://www.php.net/manual/en/function.sha1-file.php
     *
     * @return  void
     * @since   1.0
     */
    public function hashFileSha1()
    {
        $this->hash_file_sha1 = null;

        if ($this->exists === true) {

        } elseif ($this->is_file === true) {

        } else {
            return;
        }

        $this->hash_file_sha1 = sha1_file($this->path);

        return;
    }

    /**
     * Hash file sha1 20
     * http://www.php.net/manual/en/function.sha1-file.php
     *
     * @return  void
     * @since   1.0
     */
    public function hashFileSha1_20()
    {
        $this->hash_file_sha1_20 = null;

        if ($this->exists === true) {

        } elseif ($this->is_file === true) {

        } else {
            return;
        }

        $this->hash_file_sha1_20 = sha1_file($this->path, true);

        return;
    }
}
