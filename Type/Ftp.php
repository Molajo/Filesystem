<?php
/**
 * FTP Adapter for Filesystem
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Filesystem\Type;

defined('MOLAJO') or die;

/**
 * FTP Filesystem Type
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class FTP extends FilesystemType
{
    /**
     * @var $temp for holding file name
     */
    private $temp;

    /**
     * @var used when parsing ftprawlist data that is then used for determining metadata values
     */
    private $temp_files = array();

    /**
     * @var  $is_windows;
     */
    public $is_windows;

    /**
     * Class constructor
     *
     * @since   1.0
     */
    public function __construct()
    {
        parent::__construct();

        $this->setFilesystemType('Ftp');

        return $this;
    }

    /**
     * Method to connect and logon to a FTP server
     *
     * @param   array   $options
     *
     * @return  void
     * @since   1.0
     */
    public function connect($options = array())
    {
        //todo can this be a stream?
        $this->temp = '/Users/amystephen/Sites/Filesystem/.dev/Tests/Hold/amy.txt';

        parent::connect($options);

        if ($this->is_connected === true) {
            return;
        }

        try {
            if ($this->getConnectType() == 'ftps') {

                if (function_exists('ftp_ssl_connect')) {
                    throw new InvalidArgumentException
                    ('ftp_ssl_connect must be enabled in PHP to use SSL over FTP');
                }

                $id = \ftp_ssl_connect($this->host, $this->port, $this->timeout);

            } else {
                $id = \ftp_connect($this->host, $this->port, $this->timeout);
            }

            $this->setConnection($id);

        } catch (\Exception $e) {
            throw new \InvalidArgumentException
            ('Filesystem Adapter FTP: Unable to connect to the FTP Server '
                . ' Host: ' . $this->host . ' Port: ' . $this->port);
        }

        if ($this->is_connected === false) {
            throw new \InvalidArgumentException
            ('Filesystem Adapter FTP: Not connected '
                . ' Host: ' . $this->host . ' Port: ' . $this->port);
        }

        try {
            \ftp_pasv($this->connection, $this->getPassiveMode());

        } catch (\Exception $e) {

            throw new \InvalidArgumentException
            ('Filesystem Adapter FTP: Unable to set passive mode for FTP Server '
                . ' Host: ' . $this->host . ' Port: ' . $this->port);
        }

        try {
            $this->login();

        } catch (\Exception $e) {

            throw new \InvalidArgumentException
            ('Filesystem Adapter FTP: Login failed for ' . ' User: ' . $this->username
                . ' Host: ' . $this->host . ' Port: ' . $this->port);
        }

        try {
            $this->is_windows = false;

            $ftpSystemType = \ftp_systype($this->getConnection());

            if (stripos($ftpSystemType, 'win') == false) {
                $this->is_windows = false;
            } else {
                $this->is_windows = true;
            }

        } catch (\Exception $e) {

            throw new \InvalidArgumentException
            ('Filesystem Adapter FTP: Login failed for ' . ' User: ' . $this->username
                . ' Host: ' . $this->host . ' Port: ' . $this->port);
        }

        try {
            if ($this->initial_directory === null) {
                $results = true;
            } else {
                $results = \ftp_chdir($this->connection, $this->initial_directory);
            }

        } catch (\Exception $e) {

            throw new \InvalidArgumentException
            ('Filesystem Adapter FTP: Changing FTP Directory failed. Directory: '
                . $this->initial_directory);
        }

        if ($results === false) {

            throw new \InvalidArgumentException
            ('Filesystem Adapter FTP: Unable to change directory: '
                . $this->root . ' for FTP Server '
                . ' Host: ' . $this->host . ' Port: ' . $this->port);
        }

        return;
    }

    /**
     * Method to login to a server once connected
     *
     * @return  bool
     * @since   1.0
     * @throws  \RuntimeException
     */
    public function login()
    {
        $logged_in = ftp_login($this->getConnection(), $this->getUsername(), $this->getPassword());

        if ($logged_in === true) {
        } else {
            throw new \RuntimeException
            ('Filesystem Adapter FTP: Unable to login with Password: ' . $this->getPassword()
                . ' Password: ' . $this->getPassword());
        }

        return true;
    }

    /**
     * Adapter Interface Step 2:
     * Set the Path
     *
     * @param   string  $path
     *
     * @return  string
     * @since   1.0
     */
    public function setPath($path)
    {
        return parent::setPath($path);
    }

    /**
     * Adapter Interface Step 3:
     *
     * Retrieves and sets metadata for the file specified in path
     *
     * @return  void
     * @since   1.0
     */
    public function getMetadata()
    {
        $this->getFTPMetadata();

        parent::getMetadata();

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
            ('FTP Filesystem Read: File does not exist: ' . $this->path);
        }

        if ($this->is_file === false) {
            throw new NotFoundException
            ('FTP Filesystem Read: Is not a file: ' . $this->path);
        }

        /** Already available in $this->temp file */
        try {
            $this->data = file_get_contents($this->temp);

        } catch (\Exception $e) {

            throw new NotFoundException
            ('FTP Filesystem Read: Error reading file: ' . $this->path);
        }

        if ($this->data === false) {
            ('FTP Filesystem Read: Empty File: ' . $this->path);
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
     * @param   string  $file
     * @param   string  $data
     * @param   bool    $replace
     * @param   bool    $append
     * @param   bool    $truncate
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
            ('FTP Filesystem Write: must be directory or file: '
                . $this->path . '/' . $file);
        }

        if (trim($data) == '' || strlen($data) == 0) {
            if ($this->is_file === true) {

                throw new FilesystemException
                ('FTP Filesystem: attempting to write no data to file: '
                    . $this->path . '/' . $file);
            }
        }

        if (trim($data) == '' || strlen($data) == 0) {
            if ($file == '') {
                throw new FilesystemException
                ('FTP Filesystem: attempting to write no data to file: '
                    . $this->path . '/' . $file);

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
            ('FTP Filesystem: file is not writable: ' . $this->path . '/' . $file);
        }

        if (file_exists($this->path . '/' . $file)) {

            if ($replace === false) {
                throw new FilesystemException
                ('FTP Filesystem: attempting to write to existing file: '
                    . $this->path . '/' . $file);
            }

            \unlink($this->path . '/' . $file);
        }

        try {
            \file_put_contents($this->path . '/' . $file, $data);

        } catch (\Exception $e) {

            throw new NotFoundException
            ('FTP Filesystem: error writing file ' . $this->path . '/' . $file);
        }

        return;
    }

    /**
     * Append data to file identified in path using the data value
     *
     * @param   string  $file
     * @param   string  $data
     * @param   bool    $replace
     * @param   bool    $append
     * @param   bool    $truncate
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
            ('FTP Filesystem: attempting to append to a folder, not a file ' . $this->path);
        }

        if ($replace === true || $truncate === true) {
            throw new FilesystemException
            ('FTP Filesystem Write: replace and truncate must both be false for append action: '
                . $this->path);
        }

        try {
            \file_put_contents($this->path, $data, FILE_APPEND);

        } catch (\Exception $e) {

            throw new NotFoundException
            ('FTP Filesystem: error appending to file ' . $this->path);
        }

        return;
    }

    /**
     * Truncate file identified in path using the data value
     *
     * @param   string  $file
     * @param   string  $data
     * @param   bool    $replace
     * @param   bool    $append
     * @param   bool    $truncate
     *
     * @return  void
     * @since   1.0
     */
    private function truncate($file = '', $data = '', $replace = false, $append = false, $truncate = false)
    {
        if ($this->exists === false) {
            throw new FilesystemException
            ('FTP Filesystem: attempting to truncate file that does not exist. '
                . $this->path);
        }

        if ($this->is_file === true) {
        } else {
            throw new FilesystemException
            ('FTP Filesystem: only a file can be truncated. ' . $this->path);
        }

        if ($replace === true || $append === true) {
            throw new FilesystemException
            ('FTP Filesystem Write: replace and append must both be false for truncate action: '
                . $this->path . '/' . $file);
        }

        if (trim($data) == '' || strlen($data) == 0) {
        } else {
            throw new FilesystemException
            ('FTP Filesystem: data cannot be defined for truncate action. ' . $this->path);
        }

        if ($this->isWriteable($this->path) === false) {
            throw new FilesystemException
            ('FTP Filesystem: file is not writable and cannot be truncated: ' . $this->path);
        }

        try {
            $fp = \fopen($this->path, "w");
            fclose($fp);

        } catch (\Exception $e) {

            throw new FilesystemException
            ('FTP Filesystem: error truncating file ' . $this->path);
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

        } catch (\Exception $e) {

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
            ('FTP Filesystem Delete: Request to delete root is not allowed' . $this->path);
        }

        if (file_exists($this->path)) {
        } else {

            throw new NotFoundException
            ('FTP Filesystem Delete: File not found ' . $this->path);
        }

        if ($this->is_writable === false) {

            throw new FilesystemException
            ('FTP Filesystem Delete: No write access to file/path: ' . $this->path);
        }

        try {

            if (file_exists($this->path)) {
            } else {
                return;
            }

            $this->_deleteDiscoveryFilesArray();

            $this->_deleteDiscoveryDirectoriesArray();

        } catch (\Exception $e) {

            throw new FilesystemException
            ('FTP Filesystem Delete: failed for: ' . $this->path);
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
     * Note: $target_filesystem_type is an instance of the Filesystem exclusive to the target
     *  portion of the copy
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
            $target_filesystem_type = $this->getFilesystemType();
        }

        $this->moveOrCopy($target_directory, $target_name,
            $replace, $target_filesystem_type, 'copy');

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
            $target_filesystem_type = $this->getFilesystemType();
        }

        $this->moveOrCopy($target_directory, $target_name,
            $replace, $target_filesystem_type, 'move');

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
     * @return  void|void
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
                ('FTP Filesystem ' . $move_or_copy
                    . ': Must specify new file name when using the same target path: '
                    . $this->path);
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
            ('FTP Filesystem moveOrCopy: failed. This path does not exist: '
                . $this->path . ' Specified as source for ' . $move_or_copy
                . ' operation to ' . $target_directory);
        }

        if (file_exists($target_directory)) {
        } else {
            throw new FilesystemException
            ('FTP Filesystem moveOrCopy: failed. This path does not exist: '
                . $this->path . ' Specified as destination for ' . $move_or_copy
                . ' to ' . $target_directory);
        }

        if (is_writeable($target_directory) === false) {
            throw new FilesystemException
            ('FTP Filesystem Delete: No write access to file/path: ' . $target_directory);
        }

        if ($move_or_copy == 'move') {
            if (is_writeable($this->path) === false) {
                throw new FilesystemException
                ('FTP Filesystem Delete: No write access for moving source file/path: '
                    . $move_or_copy);
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
     * Change the file mode for user for read, write, execute access
     *
     * @param   int  $permission
     *
     * @return  void
     * @throws  FilesystemException
     * @since   1.0
     */
    public function changePermission($permission)
    {
        $permission = octdec(str_pad($permission, 4, '0', STR_PAD_LEFT));

        try {
            chmod($this->path, $permission);

        } catch (\Exception $e) {

            throw new FilesystemException
            ('FTP Filesystem: changePermission method failed for ' . $permission);
        }
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

        } catch (\Exception $e) {

            throw new FilesystemException
            ('FTP Filesystem: is_readable method failed for ' . $this->path);
        }

        return;
    }

    /**
     * Get FTP Metadata for parsing and using to see if the path exists
     *
     * @return void
     */
    function getFTPMetadata()
    {
        $ftp_metadata = ftp_rawlist($this->getConnection(), $this->path);

        $this->temp_files = array();

        foreach ($ftp_metadata as $key => $fileMetadata) {

            $metadata              = new \stdClass();
            $metadata->permissions = substr($fileMetadata, 0, 10);
            $fileMetadata          = trim(substr($fileMetadata, 10, 9999));
            $metadata->owner       = substr($fileMetadata, 0, strpos($fileMetadata, ' '));
            $fileMetadata          = trim(substr($fileMetadata, strpos($fileMetadata, ' '), 9999));
            $metadata->group       = substr($fileMetadata, 0, strpos($fileMetadata, ' '));
            $fileMetadata          = trim(substr($fileMetadata, strpos($fileMetadata, ' '), 9999));
            $metadata->size        = substr($fileMetadata, 0, strpos($fileMetadata, ' '));
            $fileMetadata          = trim(substr($fileMetadata, strpos($fileMetadata, ' '), 9999));
            $metadata->day         = substr($fileMetadata, 0, strpos($fileMetadata, ' '));
            $fileMetadata          = trim(substr($fileMetadata, strpos($fileMetadata, ' '), 9999));
            $metadata->month       = substr($fileMetadata, 0, strpos($fileMetadata, ' '));
            $fileMetadata          = trim(substr($fileMetadata, strpos($fileMetadata, ' '), 9999));
            $metadata->year        = substr($fileMetadata, 0, strpos($fileMetadata, ' '));
            $fileMetadata          = trim(substr($fileMetadata, strpos($fileMetadata, ' '), 9999));
            $metadata->time        = substr($fileMetadata, 0, strpos($fileMetadata, ' '));
            $fileMetadata          = trim(substr($fileMetadata, strpos($fileMetadata, ' '), 9999));
            $metadata->name        = trim($fileMetadata);

            if ($metadata->name == '.'
                || $metadata->name == '..'
            ) {

            } else {
                $name                    = $metadata->name;
                $this->temp_files[$name] = $metadata;
            }
        }

        return;
    }

    /**
     * Does the path exist (as either a file or a folder)?
     *
     * @return void
     */
    public function exists()
    {
        $this->exists = false;

        if (is_array($this->temp_files)
            && count($this->temp_files) > 0
        ) {
            $this->exists = true;
        }

        return;
    }

    /**
     * Implemented in isAbsolutePath
     *
     * @return  void
     * @since   1.0
     * @throws  NotFoundException
     */
    public function getAbsolutePath()
    {
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
            $this->absolute_path    = $this->path;
        } else {
            $this->is_absolute_path = false;
        }

        return;
    }

    /**
     * Returns true or false indicator as to whether or not the path is a directory
     *
     * @ - added to prevent PHP from throwing a warning if it is a file, not a directory
     *
     * @return  void
     * @since   1.0
     */
    public function isDirectory()
    {
        $this->is_directory = false;

        $current = ftp_pwd($this->getConnection());

        try {
            if (@ftp_chdir($this->getConnection(), $this->path)) {
                $this->is_directory = true;
            } else {
                $this->is_file = false;
            }

        } catch (\Exception $e) {

        }

        ftp_chdir($this->getConnection(), $current);

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

        try {

            if (@ftp_get($this->getConnection(), $this->temp, $this->path, FTP_ASCII, 0)) {
                $this->is_file = true;
            }

        } catch (\Exception $e) {
        }

        return;
    }

    /**
     * Not implemented
     *
     * @return  void
     * @since   1.0
     */
    public function isLink()
    {
        $this->is_link = false;
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

        if ($this->is_file === true) {
            $this->parent = substr($this->path, 0, strrpos($this->path, '/'));
            return;
        }

        $current = ftp_pwd($this->getConnection());

        try {
            chdir($this->getConnection(), $this->path);

        } catch (\Exception $e) {
        }

        try {
            ftp_cdup($this->getConnection());

            $this->parent = ftp_pwd($this->getConnection());

        } catch (\Exception $e) {
        }

        ftp_chdir($this->getConnection(), $current);

        return;
    }

    /**
     * Get the file size of a given file.
     *
     * todo: see if $recursive is needed
     *
     * @return  void
     * @since   1.0
     */
    public function getSize()
    {
        $this->size = 0;

        if (count($this->temp_files) > 0) {

            foreach ($this->temp_files as $file) {
                $this->size = $this->size + (int)$file->size;
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
            $this->mime_type = strtolower(finfo_file($php_mime, $this->temp));
            finfo_close($php_mime);

        } elseif (function_exists('mime_content_type')) {
            $this->mime_type = mime_content_type($this->temp);

        } else {
            throw new \RuntimeException
            ('FTP Filesystem: getMimeType either finfo_open or mime_content_type are required in PHP');
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

        if ($this->is_file === true) {
        } else {
            return;
        }

        $this->owner = $this->temp_files[$this->path]->owner;

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

        if ($this->is_file === true) {
        } else {
            return;
        }

        $this->group = $this->temp_files[$this->path]->group;

        return;
    }

    /**
     * Create Date is not implemented for FTP
     *
     * @return  void
     * @since   1.0
     * @throws  FilesystemException
     */
    public function getCreateDate()
    {
        $this->create_date = null;

        return;
    }

    /**
     * Not implemented
     *
     * @return  void
     * @since   1.0
     * @throws  FilesystemException
     */
    public function getAccessDate()
    {
        $this->access_date = null;

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

        if ($this->is_file === true) {
        } else {
            return;
        }

        $this->modified_date =
            $this->temp_files[$this->path]->year . '-' .
                $this->temp_files[$this->path]->month . '-' .
                $this->temp_files[$this->path]->day . ' ' .
                $this->temp_files[$this->path]->time;

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

        if ($this->is_file === true) {
        } else {
            return;
        }

        $r = substr($this->temp_files[$this->path]->permissions, 1, 1);

        if ($r == 'r') {
            $this->is_readable = true;
        } else {
            $this->is_readable = false;
        }

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

        if ($this->is_file === true) {
        } else {
            return;
        }

        $w = substr($this->temp_files[$this->path]->permissions, 2, 1);

        if ($w == 'w') {
            $this->is_writable = true;
        } else {
            $this->is_writable = false;
        }

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

        if ($this->is_file === true) {
        } else {
            return;
        }

        $x = substr($this->temp_files[$this->path]->permissions, 3, 1);

        if ($x == 'x') {
            $this->is_executable = true;
        } else {
            $this->is_executable = false;
        }

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

        $this->hash_file_md5 = md5_file($this->temp);

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

        $this->hash_file_sha1 = sha1_file($this->temp);

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

        $this->hash_file_sha1_20 = sha1_file($this->temp, true);

        return;
    }
}
