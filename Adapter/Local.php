<?php
/**
 * Local Filesystem Adapter
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Filesystem\Adapter;

defined('MOLAJO') or die;

use stdClass;
use DateTime;
use DateTimeZone;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Exception;
use Molajo\Filesystem\Exception\LocalFilesystemException;
use Molajo\Filesystem\Api\AdapterInterface;
use Molajo\Filesystem\Api\ConnectionInterface;

/**
 * Local Filesystem Adapter
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class Local implements ConnectionInterface, AdapterInterface
{
    /**
     * Constructor
     *
     * @param   string $filesystem_type
     * @param   array  $options
     *
     * @since   1.0
     */
    public function __construct($filesystem_type = 'Local')
    {
        $this->filesystem_type = 'Local';
    }

    /**
     * Adapter Interface Step 1:
     *
     * Method to connect to a Local server
     *
     * @param   array $options
     *
     * @return  $this
     * @since   1.0
     */
    public function connect($options = array())
    {
        $this->setOptions($options);

        return $this;
    }

    /**
     * Determines if file or folder identified in path exists
     *
     * @param   string $path
     *
     * @return  bool
     * @since   1.0
     * @throws  LocalFilesystemException
     * @api
     */
    public function exists($path)
    {
        $this->exists = false;

        try {
                if (file_exists($path)) {
                    $this->exists = true;

                } elseif (is_dir($path)) {
                    $this->exists = true;
                }

        } catch (Exception $e) {
            throw new LocalFilesystemException
            ('Local Filesystem: Exists Exception ' . $e->getMessage());
        }
    }

    /**
     * Returns an associative array of metadata for the file or folder specified in path
     *
     * @param   string $path
     *
     * @return  mixed
     * @since   1.0
     * @throws  LocalFilesystemException
     * @api
     */
    public function getMetadata($path)
    {
        try {

            $metadata = new stdClass();

            $metadata->exists = $this->setExists($path)->getExists();
            $metadata->exists = $this->setAbsolutePath->getAbsolutePath();
            $metadata->exists = $this->isAbsolutePath();
            $metadata->exists = $this->isRoot();
            $metadata->exists = $this->isDirectory();
            $metadata->exists = $this->isFile();
            $metadata->exists = $this->isLink();
            $metadata->exists = $this->getType();
            $metadata->exists = $this->getName();
            $metadata->exists = $this->getParent();
            $metadata->exists = $this->getExtension();
            $metadata->exists = $this->getNoextension();
            $metadata->exists = $this->getMimeType();
            $metadata->exists = $this->getOwner();
            $metadata->exists = $this->getGroup();
            $metadata->exists = $this->getCreateDate();
            $metadata->exists = $this->getAccessDate();
            $metadata->exists = $this->getModifiedDate();
            $metadata->exists = $this->isReadable();
            $metadata->exists = $this->isWriteable();
            $metadata->exists = $this->isExecutable();
            $metadata->exists = $this->hashFileMd5();
            $metadata->exists = $this->hashFileSha1();
            $metadata->exists = $this->hashFileSha1_20();

        if ($this->exists === true) {
            $this->discovery($this->path);
        }

        $metadata->exists = $this->setSize()->$this->getSize;

        return $metadata;

        } catch (Exception $e) {
            throw new LocalFilesystemException('Local Filesystem: getMetadata Exception ' . $e->getMessage());
        }
    }

    /**
     * Returns the contents of the file identified in path
     *
     * @param   string $path
     *
     * @return  null|string
     * @since   1.0
     * @throws  LocalFilesystemException
     * @api
     */
    public function read($path)
    {
        try {

            if ($path == '') {
                throw new LocalFilesystemException
                ('Filesystem Path is required, but was not provided.');
            }

            $options = array();

            $this->setPath($path);
            $this->getMetadata();
            $this->doAction('read', $options);
            $this->close();

            return $this->fs;

        } catch (Exception $e) {
            throw new LocalFilesystemException('Local Filesystem: Read Exception ' . $e->getMessage());
        }
    }

    /**
     * Returns a list of file and folder names located at path directory, optionally recursively
     *
     * @param   string $path
     * @param   bool   $recursive
     * @param   string $extensions
     *
     * @return  mixed
     * @since   1.0
     * @throws  LocalFilesystemException
     * @api
     */
    public function getList($path, $recursive = false, $extensions = '')
    {
        try {

            if ($path == '') {
                throw new LocalFilesystemException
                ('Filesystem Path is required, but was not provided.');
            }

            $options              = array();
            $options['recursive'] = $recursive;

            $this->setPath($path);
            $this->getMetadata();
            $this->doAction('getList', $options);
            $this->close();

            return $this->fs;

        } catch (Exception $e) {
            throw new LocalFilesystemException('Local Filesystem: getList Exception ' . $e->getMessage());
        }
    }

    /**
     * Creates (or replaces) the file or creates the directory identified in path;
     *
     * @param   string $path
     * @param   string $data    file, only
     * @param   bool   $replace file, only
     *
     * @return  $this
     * @since   1.0
     * @throws  LocalFilesystemException
     * @api
     */
    public function write($path, $data = '', $replace = true)
    {
        try {

            if ($path == '') {
                throw new LocalFilesystemException
                ('Filesystem Path is required, but was not provided.');
            }

            $options            = array();
            $options['file']    = $file;
            $options['replace'] = $replace;
            $options['data']    = $data;

            $this->setPath($path);
            $this->getMetadata();
            $this->doAction('write', $options);
            $this->close();

            return $this->fs;

        } catch (Exception $e) {
            throw new LocalFilesystemException('Local Filesystem: Write Exception ' . $e->getMessage());
        }
    }

    /**
     * Deletes the file or folder identified in path, optionally deletes recursively
     *
     * @param   string $path
     * @param   bool   $recursive
     *
     * @return  $this
     * @since   1.0
     * @throws  LocalFilesystemException
     * @api
     */
    public function delete($path, $recursive = false)
    {
        try {

            if ($path == '') {
                throw new LocalFilesystemException
                ('Filesystem Path is required, but was not provided.');
            }

            $options                          = array();
            $options['delete_subdirectories'] = $delete_subdirectories;

            $this->setPath($path);
            $this->getMetadata();
            $this->doAction('delete', $options);
            $this->close();

            return $this->fs;

        } catch (Exception $e) {
            throw new LocalFilesystemException('Local Filesystem: Delete Exception ' . $e->getMessage());
        }
    }

    /**
     * Copies the file/folder in $path to the target_directory (optionally target_name),
     *  replacing content, if indicated. Can copy to target_filesystem_type.
     *
     * @param   string $path
     * @param   string $target_directory
     * @param   string $target_name
     * @param   bool   $replace
     * @param   string $target_filesystem_type
     *
     * @return  $this
     * @since   1.0
     * @throws  LocalFilesystemException
     * @api
     */
    public function copy(
        $path,
        $target_directory,
        $target_name = '',
        $replace = true,
        $target_filesystem_type = ''
    ) {
        try {

            if ($path == '') {
                throw new LocalFilesystemException
                ('Filesystem Path is required, but was not provided.');
            }

            $options                           = array();
            $options['target_directory']       = $target_directory;
            $options['target_name']            = $target_name;
            $options['replace']                = $replace;
            $options['target_filesystem_type'] = $target_filesystem_type;

            $this->setPath($path);
            $this->getMetadata();
            $this->doAction('copy', $options);
            $this->close();

            return $this->fs;

        } catch (Exception $e) {
            throw new LocalFilesystemException('Local Filesystem: Copy Exception ' . $e->getMessage());
        }
    }

    /**
     * Moves the file/folder in $path to the target_directory (optionally target_name),
     *  replacing content, if indicated. Can move to target_filesystem_type.
     *
     * @param   string $path
     * @param   string $target_directory
     * @param   string $target_name
     * @param   bool   $replace
     * @param   string $target_filesystem_type
     *
     * @return  $this
     * @since   1.0
     * @throws  LocalFilesystemException
     * @api
     */
    public function move(
        $path,
        $target_directory,
        $target_name = '',
        $replace = true,
        $target_filesystem_type = ''
    ) {
        try {

            if ($path == '') {
                throw new LocalFilesystemException
                ('Filesystem Path is required, but was not provided.');
            }

            $options                           = array();
            $options['target_directory']       = $target_directory;
            $options['target_name']            = $target_name;
            $options['replace']                = $replace;
            $options['target_filesystem_type'] = $target_filesystem_type;

            $this->setPath($path);
            $this->getMetadata();
            $this->doAction('move', $options);
            $this->close();

            return $this->fs;

        } catch (Exception $e) {
            throw new LocalFilesystemException('Local Filesystem: Move Exception ' . $e->getMessage());
        }
    }

    /**
     * Rename file or folder identified in path
     *
     * @param   string $path
     * @param   string $new_name
     *
     * @return  $this
     * @since   1.0
     * @throws  LocalFilesystemException
     * @api
     */
    public function rename($path, $new_name)
    {
        try {

            return $this->fsType->rename($path, $new_name);

        } catch (Exception $e) {
            throw new LocalFilesystemException('Local Filesystem: Rename Exception ' . $e->getMessage());
        }
    }

    /**
     * Change owner for file or folder identified in path
     *
     * @param   string $path
     * @param   string $user_name
     * @param   bool   $recursive
     *
     * @return  $this
     * @since   1.0
     * @throws  LocalFilesystemException
     * @api
     */
    public function changeOwner($path, $user_name, $recursive = false)
    {
        try {

            return $this->fsType->changeOwner($path, $user_name, $recursive);

        } catch (Exception $e) {
            throw new LocalFilesystemException('Local Filesystem: changeOwner Exception ' . $e->getMessage());
        }
    }

    /**
     * Change group for file or folder identified in path
     *
     * @param   string $path
     * @param   string $group_id
     * @param   bool   $recursive
     *
     * @return  $this
     * @since   1.0
     * @throws  LocalFilesystemException
     * @api
     */
    public function changeGroup($path, $group_id, $recursive = false)
    {
        try {

            return $this->fsType->changeGroup($path, $group_id, $recursive);

        } catch (Exception $e) {
            throw new LocalFilesystemException('Local Filesystem: changeGroup Exception ' . $e->getMessage());
        }
    }

    /**
     * Change permissions for file or folder identified in path
     *
     * @param   string $path
     * @param   int    $permission
     * @param   bool   $recursive
     *
     * @return  $this
     * @since   1.0
     * @throws  LocalFilesystemException
     * @api
     */
    public function changePermission($path, $permission, $recursive = false)
    {
        try {

            return $this->fsType->changePermission($path, $permission, $recursive);

        } catch (Exception $e) {
            throw new LocalFilesystemException('Local Filesystem: changePermission Exception ' . $e->getMessage());
        }
    }

    /**
     * Update the modification time and access time (touch) for the directory or file identified in the path
     *
     * @param   string $path
     * @param   int    $modification_time
     * @param   int    $access_time
     * @param   bool   $recursive
     *
     * @return  $this
     * @since   1.0
     * @throws  LocalFilesystemException
     * @api
     */
    public function touch($path, $modification_time = null, $access_time = null, $recursive = false)
    {
        try {

            return $this->fsType->touch($path, $modification_time, $access_time, $recursive);

        } catch (Exception $e) {
            throw new LocalFilesystemException('Local Filesystem: Touch Exception ' . $e->getMessage());
        }
    }

    /**
     * Close the Connection
     *
     * @return  $this
     * @since   1.0
     * @throws  LocalFilesystemException
     * @api
     */
    public function close()
    {
        $this->fsType->close();

        return $this;
    }

    /**
     * Rename file or folder identified in path
     *
     * @param   string $path
     * @param   string $new_name
     *
     * @return  $this
     * @since   1.0
     * @throws  LocalFilesystemException
     * @api
     */
    public function rename($path, $new_name)
    {

    }

    /**
     * Change owner for file or folder identified in path
     *
     * @param   string $path
     * @param   string $user_name
     * @param   bool   $recursive
     *
     * @return  $this
     * @since   1.0
     * @throws  LocalFilesystemException
     * @api
     */
    public function changeOwner($path, $user_name, $recursive = false)
    {
        if ($path == '') {
            throw new LocalFilesystemException
            ('Filesystem Path is required, but was not provided.');
        }

        $options              = array();
        $options['user_name'] = $user_name;

        $this->setPath($path);
        $this->getMetadata();
        $this->doAction('changeOwner', $options);
        $this->close();

        return $this->fs;
    }

    /**
     * Change group for file or folder identified in path
     *
     * @param   string $path
     * @param   string $group_id
     * @param   bool   $recursive
     *
     * @return  $this
     * @since   1.0
     * @throws  LocalFilesystemException
     * @api
     */
    public function changeGroup($path, $group_id, $recursive = false)
    {
        if ($path == '') {
            throw new LocalFilesystemException
            ('Filesystem Path is required, but was not provided.');
        }

        $options             = array();
        $options['group_id'] = $group_id;

        $this->setPath($path);
        $this->getMetadata();
        $this->doAction('changeGroup', $options);
        $this->close();

        return $this->fs;
    }

    /**
     * Change permissions for file or folder identified in path
     *
     * @param   string $path
     * @param   int    $permission
     * @param   bool   $recursive
     *
     * @return  $this
     * @since   1.0
     * @throws  LocalFilesystemException
     * @api
     */
    public function changePermission($path, $permission, $recursive = false)
    {
        if ($path == '') {
            throw new LocalFilesystemException
            ('Filesystem Path is required, but was not provided.');
        }

        $options               = array();
        $options['permission'] = $permission;

        $this->setPath($path);
        $this->getMetadata();
        $this->doAction('changePermission', $options);
        $this->close();

        return $this->fs;
    }

    /**
     * Update the modification time and access time (touch) for the directory or file identified in the path
     *
     * @param   string $path
     * @param   int    $modification_time
     * @param   int    $access_time
     *
     * @return  $this
     * @since   1.0
     * @throws  LocalFilesystemException
     * @api
     */
    public function touch($path, $modification_time = null, $access_time = null)
    {
        if ($path == '') {
            throw new LocalFilesystemException
            ('Filesystem Path is required, but was not provided.');
        }

        $options                      = array();
        $options['modification_time'] = $modification_time;
        $options['access_time']       = $access_time;

        $this->setPath($path);
        $this->getMetadata();
        $this->doAction('touch', $options);
        $this->close();

        return $this->fs;
    }

    ////////// protected /////////////

    /**
     * Set the Path
     *
     * @param   string $path
     *
     * @return  $this
     * @since   1.0
     * @throws  LocalFilesystemException
     */
    protected function setPath($path)
    {
        $this->fs->setPath($path);

        return $this;
    }


    /**
     * Get the Path
     *
     * @since   1.0
     * @return  string
     */
    protected function getPath()
    {
        return $this->path;
    }

    /**
     * Set Options
     *
     * @param   array $options
     *
     * @return  $this
     * @since   1.0
     */
    protected function setOptions($options = array())
    {
        if (is_array($options)) {
        } else {
            $options = array();
        }

        $this->options = $options;

        $this->setTimezone();
        $this->setUsername();
        $this->setPassword();
        $this->setHost();
        $this->setPort();
        $this->setFtpMode();
        $this->setConnectionType();
        $this->setTimeout();
        $this->setPassiveMode();
        $this->setInitialDirectory();

        $this->setRoot();
        $this->setDirectoryDefaultPermissions();
        $this->setFileDefaultPermissions();
        $this->setReadOnly();

        return $this;
    }

    /**
     * Set Timezone
     *
     * @return  $this
     * @since   1.0
     */
    protected function setTimezone()
    {
        $tz = '';

        if (isset($this->options['timezone'])) {
            $tz = $this->options['timezone'];
        }

        if ($tz === '') {
            if (ini_get('date.timezone')) {
                $tz = ini_get('date.timezone');
            }
        }

        if ($tz === '') {
            $tz = 'UTC';
        }

        ini_set('date.timezone', $tz);

        $this->timezone = new DateTimeZone($tz);

        return $this;
    }

    /**
     * Get Timezone
     *
     * @return  mixed
     * @since   1.0
     */
    protected function getTimezone()
    {
        return $this->timezone;
    }


    /**
     * Set the username
     *
     * @return  void
     * @since   1.0
     */
    protected function setUsername()
    {
        $username = null;

        if (isset($this->options['username'])) {
            $username = $this->options['username'];
        }

        if ($username === null) {
            $this->username = 'anonymous';
        } else {
            $this->username = $username;
        }

        return;
    }

    /**
     * Get the username
     *
     * @return  string
     * @since   1.0
     */
    protected function getUsername()
    {
        return $this->username;
    }

    /**
     * Set the password
     *
     * @return  $this
     * @since   1.0
     */
    protected function setPassword()
    {
        $password = null;

        if (isset($this->options['password'])) {
            $password = $this->options['password'];
        }

        $this->password = $password;

        return $this;
    }

    /**
     * Get the password
     *
     * @return  mixed
     * @since   1.0
     */
    protected function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the Host
     *
     * @return  $this
     * @since   1.0
     */
    protected function setHost()
    {
        $host = null;

        if (isset($this->options['host'])) {
            $host = $this->options['host'];
        }

        $this->host = $host;

        return $this;
    }

    /**
     * Get the Host
     *
     * @return  string
     * @since   1.0
     */
    protected function getHost()
    {
        return $this->host;
    }

    /**
     * Set the Port
     *
     * @return  $this
     * @since   1.0
     */
    protected function setPort()
    {
        $port = null;

        if (isset($this->options['port'])) {
            $port = $this->options['port'];
        }

        $this->port = $port;

        return $this;
    }

    /**
     * Get the Port
     *
     * @return  string
     * @since   1.0
     */
    protected function getPort()
    {
        return $this->port;
    }

    /**
     * Set the FTP Mode
     *
     * @return  $this
     * @since   1.0
     */
    protected function setFtpMode()
    {
        $ftp_mode = null;

        if (isset($this->options['ftp_mode'])) {
            $ftp_mode = $this->options['ftp_mode'];
        }

        $this->ftp_mode = $ftp_mode;

        return $this;
    }

    /**
     * Get the FTP Mode
     *
     * @return  string
     * @since   1.0
     */
    protected function getFtpMode()
    {
        return $this->ftp_mode;
    }

    /**
     * Set the Connection Type
     *
     * setFtpMode
     *
     * @return  $this
     * @since   1.0
     */
    protected function setConnectionType()
    {
        $connection_type = null;

        if (isset($this->options['connection_type'])) {
            $connection_type = $this->options['connection_type'];
        }

        if ($connection_type === null) {
            $this->connection_type = strtolower($this->getFilesystemType());
        } else {
            $this->connection_type = strtolower($connection_type);
        }

        return $this;
    }

    /**
     * Get the Connection Type
     *
     * @return  string
     * @since   1.0
     */
    protected function getConnectionType()
    {
        return $this->connection_type;
    }

    /**
     * Set the Timeout (default 900 seconds/15 minutes)
     *
     * @param   int $timeout
     *
     * @return  $this
     * @since   1.0
     */
    protected function setTimeout($timeout = 900)
    {
        $timeout = null;

        if (isset($this->options['timeout'])) {
            $timeout = $this->options['timeout'];
        }

        if ($timeout === null) {
            $this->timeout = 900;
        } else {
            $this->timeout = $timeout;
        }

        return $this;
    }

    /**
     * Get the Timeout
     *
     * @return  int
     * @since   1.0
     */
    protected function getTimeout()
    {
        return (int)$this->timeout;
    }

    /**
     * Set the Passive Indicator
     *
     * @return  $this
     * @since   1.0
     */
    protected function setPassiveMode()
    {
        $passive_mode = null;

        if (isset($this->options['passive_mode'])) {
            $passive_mode = $this->options['passive_mode'];
        }

        if ($passive_mode === true) {
            $this->passive_mode = true;
        } else {
            $this->passive_mode = false;
        }

        return $this;
    }

    /**
     * Get Passive Mode Setting
     *
     * @return  string
     * @since   1.0
     */
    protected function getPassiveMode()
    {
        return $this->passive_mode;
    }

    /**
     * Set Initial Directory for the System Connection
     *
     * @return  $this
     * @since   1.0
     */
    protected function setInitialDirectory()
    {
        $initial_directory = null;

        if (isset($this->options['initial_directory'])) {
            $initial_directory = $this->options['initial_directory'];
        }

        if ($initial_directory === null) {
        } else {
            $this->initial_directory = $initial_directory;
        }

        return $this;
    }

    /**
     * Get Initial Directory
     *
     * @return string
     * @since   1.0
     */
    protected function getInitialDirectory()
    {
        return $this->initial_directory;
    }

    /**
     * Set Root of Filesystem
     *
     * @return  $this
     * @since   1.0
     * @throws  LocalFilesystemException
     */
    protected function setRoot()
    {
        $root = '';

        if (isset($this->options['root'])) {
            $root = $this->options['root'];
        }

        if ($root === '') {
            $root = '/';
        }

        if (file_exists($root)) {
            if (is_dir($root)) {
                $this->root = $root;

                return $this;
            }
        }

        throw new LocalFilesystemException
        ('Filesystem Local: Root is not a valid directory. ' . $root);
    }

    /**
     * Get Root of Filesystem
     *
     * @return  $this
     * @since   1.0
     */
    protected function getRoot()
    {
        return $this->root;
    }

    /**
     * Set Directory Permissions for Filesystem
     *
     * @return  $this
     * @since   1.0
     */
    protected function setDirectoryDefaultPermissions()
    {
        $default_directory_permissions = null;

        if (isset($this->options['default_directory_permissions'])) {
            $default_directory_permissions = $this->options['default_directory_permissions'];
        }

        if ($default_directory_permissions === null) {
            $this->default_directory_permissions = 0755;
        } else {
            $this->default_directory_permissions = $default_directory_permissions;
        }

        return $this;
    }

    /**
     * Get Directory Permissions for Filesystem
     *
     * @return  string
     * @since   1.0
     */
    protected function getDirectoryDefaultPermissions()
    {
        return $this->default_directory_permissions;
    }

    /**
     * Set Default Filesystem Permissions for Files
     *
     * @return  $this
     * @since   1.0
     */
    protected function setFileDefaultPermissions()
    {
        $default_file_permissions = null;

        if (isset($this->options['default_file_permissions'])) {
            $default_file_permissions = $this->options['default_file_permissions'];
        }

        if ($default_file_permissions === null) {
            $this->default_file_permissions = 0644;
        } else {
            $this->default_file_permissions = $default_file_permissions;
        }

        return $this;
    }

    /**
     * Get File Permissions for Filesystem
     *
     * @return  int
     * @since   1.0
     */
    protected function getFileDefaultPermissions()
    {
        return $this->default_file_permissions;
    }

    /**
     * Set Read Only Setting for Filesystem
     *
     * @return  $this
     * @since   1.0
     */
    protected function setReadOnly()
    {
        $read_only = false;

        if (isset($this->options['read_only'])) {
            $read_only = $this->options['read_only'];
        }

        if ($read_only === true) {
            $this->read_only = true;
        } else {
            $this->read_only = false;
        }

        return $this;
    }

    /**
     * Get Read Only for Filesystem
     *
     * @return  bool
     * @since   1.0
     */
    protected function getReadOnly()
    {
        return $this->read_only;
    }

    /**
     * Set Connection
     *
     * @param resource $connection
     *
     * @return  $this
     * @since   1.0
     */
    protected function setConnection($connection)
    {
        $this->connection = $connection;

        $this->is_connected = false;

        if ($this->connection === null || $this->connection === false) {
        } else {
            $this->is_connected = true;
        }

        $this->is_connected = $this->setTorF($this->is_connected, true);

        return $this;
    }

    /**
     * get Connection
     *
     * @return  mixed
     * @since   1.0
     */
    protected function getConnection()
    {
        return $this->connection;
    }

    /**
     * Set Connection
     *
     * @return  $this
     * @since   1.0
     */
    protected function setIsConnected()
    {
        $this->is_connected = false;

        if ($this->connection === null || $this->connection === false) {
        } else {
            $this->is_connected = true;
        }

        $this->is_connected = $this->setTorF($this->is_connected, true);

        return $this;
    }

    /**
     * get IsConnected
     *
     * @return  $this
     * @since   1.0
     */
    protected function getIsConnected()
    {
        return $this->is_connected;
    }

    /**
     * Retrieves the absolute path, which is the relative path from the root directory,
     *  prepended with a '/'.
     *
     * @return  $this
     * @since   1.0
     * @throws  LocalFilesystemException
     */
    protected function getAbsolutePath()
    {
        if ($this->exists === false) {
            $this->absolute_path = null;

            return $this;
        }

        $this->absolute_path = realpath($this->path);

        return $this;
    }

    /**
     * Indicates whether the given path is absolute or not
     *
     * Relative path - describes how to get from a particular directory to a file or directory
     * Absolute Path - relative path from the root directory, prepended with a '/'.
     *
     * @return  $this
     * @since   1.0
     */
    protected function isAbsolutePath()
    {
        if ($this->exists === false) {
            $this->is_absolute_path = null;

            return $this;
        }

        if (substr($this->path, 0, 1) == '/') {
            $this->is_absolute_path = true;
            $this->absolute_path    = $this->path;
        } else {
            $this->is_absolute_path = false;
        }

        return $this;
    }

    /**
     * Is this the root folder?
     *
     * @return  $this
     * @since   1.0
     * @throws  LocalFilesystemException
     */
    protected function isRoot()
    {
        $this->is_root = false;

        if ($this->path == $this->root) {
            $this->is_root = true;
        } else {
            if ($this->path == '/' || $this->path == '\\') {
                $this->is_root = true;
            }
        }

        return $this;
    }

    /**
     * Returns true or false indicator as to whether or not the path is a directory
     *
     * @return  $this
     * @since   1.0
     */
    protected function isDirectory()
    {
        $this->is_directory = false;

        if (is_dir($this->path)) {
            $this->is_directory = true;
        }

        return $this;
    }

    /**
     * Returns true or false indicator as to whether or not the path is a file
     *
     * @return  bool
     * @since   1.0
     */
    protected function isFile()
    {
        $this->is_file = false;

        if (is_file($this->path)) {
            $this->is_file = true;
        }

        return $this->is_file;
    }

    /**
     * Returns true or false indicator as to whether or not the path is a link
     *
     * @return  bool
     * @since   1.0
     */
    protected function isLink()
    {
        $this->is_link = false;

        if (is_link($this->path)) {
            $this->is_link = true;
        }

        return $this->is_link;
    }

    /**
     * Returns the value 'directory, 'file' or 'link' for the type determined
     *  from the path
     *
     * @return  null|string
     * @since   1.0
     * @throws  LocalFilesystemException
     * @throws  LocalFilesystemException
     */
    protected function getType()
    {
        if ($this->exists === false) {
            $this->type = null;

        } elseif ($this->is_directory === true) {
            $this->type = 'directory';

        } elseif ($this->is_file === true) {
            $this->type = 'file';

        } elseif ($this->is_link === true) {
            $this->type = 'link';

        } else {

            throw new LocalFilesystemException
            ('Not a directory, file or a link.');
        }

        return $this->type;
    }

    /**
     * Get Parent
     *
     * @return  null|string
     * @since   1.0
     * @throws  LocalFilesystemException
     */
    protected function getParent()
    {
        if ($this->exists === false) {
            $this->parent = null;

        } elseif ($this->is_root === true) {
            $this->parent = null;

        } else {
            $this->parent = pathinfo($this->path, PATHINFO_DIRNAME);
        }

        return $this->parent;
    }

    /**
     * Get File or Directory Name
     *
     * @return  string
     * @since   1.0
     * @throws  LocalFilesystemException
     */
    protected function getName()
    {
        if ($this->exists === false) {
            $this->name = null;
        } else {

            $this->name = pathinfo($this->path, PATHINFO_BASENAME);
        }

        return $this->name;
    }

    /**
     * Get File Extension
     *
     * @return  null|string
     * @since   1.0
     * @throws  LocalFilesystemException
     */
    protected function getExtension()
    {
        if ($this->exists === false) {
            $this->extension = null;

            return null;
        }

        if ($this->is_file === true) {

        } elseif ($this->is_directory === true) {
            $this->extension = null;

            return null;

        } else {
            throw new LocalFilesystemException
            (ucfirst(strtolower($this->getFilesystemType())) . ' Filesystem:  not a valid file. Path: '
                . $this->path);
        }

        $this->extension = pathinfo($this->path, PATHINFO_EXTENSION);

        return $this->extension;
    }

    /**
     * Get File without Extension
     *
     * @return  string
     * @since   1.0
     * @throws  LocalFilesystemException
     */
    protected function getNoExtension()
    {
        if ($this->exists === false) {
            $this->no_extension = null;

            return null;
        }

        if ($this->is_file === true) {

        } elseif ($this->is_directory === true) {
            $this->no_extension = null;

            return null;

        } else {
            throw new LocalFilesystemException
            (ucfirst(strtolower($this->getFilesystemType())) . ' Filesystem:  not a valid file. Path: '
                . $this->path);
        }

        $this->no_extension = pathinfo($this->path, PATHINFO_FILENAME);

        return $this->no_extension;
    }

    /**
     * Get the file size of a given file.
     *
     * @param bool $recursive
     *
     * @return  int
     * @since   1.0
     */
    protected function getSize($recursive = true)
    {
        $this->size = 0;

        if (count($this->files) > 0 && is_array($this->files)) {

            foreach ($this->files as $file) {
                $this->size = $this->size + filesize($file);
            }
        }

        return $this->size;
    }

    /**
     * Returns the mime type of the file located at path directory
     *
     * @return  null|string
     * @since   1.0
     * @throws  LocalFilesystemException
     */
    protected function getMimeType()
    {
        $this->mime_type = null;

        if ($this->exists === false) {
            return null;
        }

        if ($this->is_file === true) {
        } else {
            return null;
        }

        if (function_exists('finfo_open')) {
            $php_mime        = finfo_open(FILEINFO_MIME);
            $this->mime_type = strtolower(finfo_file($php_mime, $this->path));
            finfo_close($php_mime);

        } elseif (function_exists('mime_content_type')) {
            $this->mime_type = mime_content_type($this->path);

        } else {
            throw new LocalFilesystemException
            (ucfirst(strtolower($this->getFilesystemType())) . ' Filesystem:  getMimeType either '
                . ' finfo_open or mime_content_type are required in PHP');
        }

        return $this->mime_type;
    }

    /**
     * Returns the owner of the file or directory defined in the path
     *
     * @return  null|int
     * @since   1.0
     */
    protected function getOwner()
    {
        $this->owner = null;

        if ($this->exists === true) {
        } else {
            return null;
        }

        $this->owner = fileowner($this->path);

        return $this->owner;
    }

    /**
     * Returns the group for the file or directory defined in the path
     *
     * @return  int
     * @since   1.0
     */
    protected function getGroup()
    {
        $this->group = null;

        if ($this->exists === true) {
        } else {
            return $this->group;
        }

        $this->group = filegroup($this->path);

        return $this->group;
    }

    /**
     * Retrieves Create Date for directory or file identified in the path
     *
     * @return  null|string
     * @since   1.0
     * @throws  LocalFilesystemException
     */
    protected function getCreateDate()
    {
        $this->create_date = null;

        if ($this->exists === true) {
        } else {
            return null;
        }

        try {
            $this->create_date = date("Y-m-d H:i:s", filectime($this->path));

        } catch (Exception $e) {

            throw new LocalFilesystemException
            (ucfirst(strtolower($this->getFilesystemType())) . ' Filesystem getCreateDate failed for ' . $this->path);
        }

        return $this->create_date;
    }

    /**
     * Retrieves Last Access Date for directory or file identified in the path
     *
     * @return  null|string
     * @since   1.0
     * @throws  LocalFilesystemException
     */
    protected function getAccessDate()
    {
        $this->access_date = null;

        if ($this->exists === true) {
        } else {
            return null;
        }

        try {
            $this->access_date = date("Y-m-d H:i:s", fileatime($this->path));

        } catch (Exception $e) {

            throw new LocalFilesystemException
            (ucfirst(strtolower($this->getFilesystemType())) . ' Filesystem getAccessDate failed for ' . $this->path);
        }

        return $this->access_date;
    }

    /**
     * Retrieves Last Update Date for directory or file identified in the path
     *
     * @return  null|string
     * @since   1.0
     * @throws  LocalFilesystemException
     */
    protected function getModifiedDate()
    {
        $this->modified_date = null;

        if ($this->exists === true) {
        } else {
            return null;
        }

        try {
            $this->modified_date = date("Y-m-d H:i:s", filemtime($this->path));

        } catch (Exception $e) {
            throw new LocalFilesystemException

            (ucfirst(strtolower($this->getFilesystemType())) . ' Filesystem:  getModifiedDate method failed for '
                . $this->path);
        }

        return $this->modified_date;
    }

    /**
     * Tests if the current user has read access
     *
     * @return  null|string
     * @since   1.0
     */
    protected function isReadable()
    {
        $this->is_readable = null;

        if ($this->exists === true) {
        } else {
            return null;
        }

        $this->is_readable = is_readable($this->path);

        return $this->is_readable;
    }

    /**
     * Tests if the current user has write access
     *
     * @return  null|string
     * @since   1.0
     */
    protected function isWriteable()
    {
        $this->is_writable = null;

        if ($this->exists === true) {
        } else {
            return null;
        }

        $this->is_writable = is_writable($this->path);

        return $this->is_writable;
    }

    /**
     * Tests if the current user has executable access
     *
     * @return  null|bool
     * @since   1.0
     */
    protected function isExecutable()
    {
        $this->is_executable = null;

        if ($this->exists === true) {
        } else {
            return null;
        }

        $this->is_executable = is_executable($this->path);

        return $this->is_executable;
    }

    /**
     * Calculates the md5 hash of a given file
     *
     * @return  null|string
     * @since   1.0
     */
    protected function hashFileMd5()
    {
        $this->hash_file_md5 = null;

        if ($this->exists === true) {

        } elseif ($this->is_file === true) {

        } else {
            return null;
        }

        $this->hash_file_md5 = md5_file($this->path);

        return $this->hash_file_md5;
    }

    /**
     * Hash file sha1
     * http://www.php.net/manual/en/function.sha1-file.php
     *
     * @return  null|string
     * @since   1.0
     */
    protected function hashFileSha1()
    {
        $this->hash_file_sha1 = null;

        if ($this->exists === true) {

        } elseif ($this->is_file === true) {

        } else {
            return null;
        }

        $this->hash_file_sha1 = sha1_file($this->path);

        return $this->hash_file_sha1;
    }

    /**
     * Hash file sha1 20
     * http://www.php.net/manual/en/function.sha1-file.php
     *
     * @return  null|string
     * @since   1.0
     */
    protected function hashFileSha1_20()
    {
        $this->hash_file_sha1_20 = null;

        if ($this->exists === true) {

        } elseif ($this->is_file === true) {

        } else {
            return null;
        }

        $this->hash_file_sha1_20 = sha1_file($this->path, true);

        return $this->hash_file_sha1_20;
    }

    /**
     * Adapter Interface Step 4:
     *
     * Execute the action requested
     *
     * @param string $action
     *
     * @return void
     * @since   1.0
     * @throws  LocalFilesystemException
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
                    throw new LocalFilesystemException
                    (ucfirst(strtolower($this->getFilesystemType()))
                        . ' Filesystem:  MTarget_directory for Copy Action. Path: ' . $this->path);
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
                    $target_filesystem_type = $this->getFilesystemType();
                }

                $this->data
                    = $this->$action($target_directory, $target_name, $replace, $target_filesystem_type);

                break;

            case 'getrelativepath':

                if (isset($this->options['relative_to_this_path'])) {
                    $relative_to_this_path = $this->options['relative_to_this_path'];

                } else {
                    throw new LocalFilesystemException
                    (ucfirst(strtolower($this->getFilesystemType()))
                        . ' Filesystem:  Must provide relative_to_this_path for relative_path request. Path: '
                        . $this->path);
                }

                $this->getRelativePath($relative_to_this_path);

                break;

            case 'changepermission':

                $mode = null;

                if (isset($this->options['mode'])) {
                    $mode = $this->options['mode'];
                }

                if ($mode === null) {
                    throw new LocalFilesystemException
                    ($this->getFilesystemType(
                    ) . ' Filesystem doAction Method ' . $action . ' no mode value provided.');
                }

                $this->changePermission((int)$mode);

                break;

            case 'touch':

                $modification_time = null;

                if (isset($this->options['modification_time'])) {
                    $modification_time = (int)$this->options['modification_time'];
                }

                $access_time = null;

                if (isset($this->options['access_time'])) {
                    $access_time = (int)$this->options['access_time'];
                }

                $this->touch($modification_time, $access_time);

                break;

            default:
                throw new LocalFilesystemException
                ($this->getFilesystemType() . ' Filesystem doAction Method ' . $action . ' does not exist.');
        }

        return;
    }

    /**
     * Returns the contents of the file identified by path
     *
     * @return  mixed
     * @since   1.0
     * @throws  LocalFilesystemException when file does not exist
     */
    protected function read()
    {
        if ($this->exists === false) {
            throw new LocalFilesystemException
            (ucfirst(strtolower($this->getFilesystemType())) . ' Filesystem Read: File does not exist: ' . $this->path);
        }

        if ($this->is_file === false) {
            throw new LocalFilesystemException
            (ucfirst(strtolower($this->getFilesystemType())) . ' Filesystem Read: Is not a file: ' . $this->path);
        }

        if ($this->is_readable === false) {
            throw new LocalFilesystemException
            (ucfirst(
                strtolower($this->getFilesystemType())
            ) . ' Filesystem Read: Not permitted to read: ' . $this->path);
        }

        try {
            $this->data = file_get_contents($this->path);

        } catch (LocalFilesystemException $e) {

            throw new LocalFilesystemException
            (ucfirst(strtolower($this->getFilesystemType())) . ' Filesystem Read: Error reading file: ' . $this->path);
        }

        if ($this->data === false) {
            (ucfirst(strtolower($this->getFilesystemType())) . ' Filesystem Read: Empty File: ' . $this->path);
        }

        return;
    }

    /**
     * Returns the contents of the files located at path directory
     *
     * @param   bool   $recursive
     * @param   bool   $exclude_files
     * @param   bool   $exclude_folders
     * @param   array  $extension_list
     * @param   string $name_mask
     *
     * @return  $this
     * @since   1.0
     */
    protected function getList(
        $recursive = false,
        $exclude_files = false,
        $exclude_folders = false,
        $extension_list = array(),
        $name_mask = null
    ) {
        if (is_file($this->path)) {
            $this->read();

            return $this;
        }

        $files = array();

        if ($exclude_folders === true) {
        } else {
            if (count($this->directories) > 0 && is_array($this->directories)) {
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
        }

        if ($exclude_files === true) {
        } else {
            if (count($this->files) > 0 && is_array($this->files)) {
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
        }

        if (count($files) > 0) {
            asort($files);
        }

        $this->data = $files;

        return $this;
    }

    /**
     * For a file request, creates, appends to, replaces or truncates the file identified in path
     * For a folder request, create is the only valid option
     *
     * @param   string $file
     * @param   string $data
     * @param   bool   $replace
     * @param   bool   $append
     * @param   bool   $truncate
     *
     * @return  $this
     * @since   1.0
     * @throws  LocalFilesystemException
     */
    protected function write($file = '', $data = '', $replace = false, $append = false, $truncate = false)
    {
        if ($append === true) {
            $this->append($data);

            return $this;
        }

        if ($truncate === true) {
            $this->truncate();

            return $this;
        }

        if ($this->exists === false) {

        } elseif ($this->is_file === true || $this->is_directory === true) {

        } else {
            throw new LocalFilesystemException
            (ucfirst(
                strtolower($this->getFilesystemType())
            ) . ' Filesystem Write: must be directory or file: ' . $this->path . '/' . $file);
        }

        if (trim($data) == '' || strlen($data) == 0) {
            if ($this->is_file === true) {

                throw new LocalFilesystemException
                (ucfirst(strtolower($this->getFilesystemType()))
                    . ' Filesystem:  attempting to write no data to file: ' . $this->path . '/' . $file);
            }
        }

        if (trim($data) == '' || strlen($data) == 0) {
            if ($file == '') {
                throw new LocalFilesystemException
                (ucfirst(strtolower($this->getFilesystemType()))
                    . ' Filesystem:  attempting to write no data to file: ' . $this->path . '/' . $file);

            } else {
                $this->createDirectory($this->path . '/' . $file);

                return $this;
            }
        }

        if (file_exists($this->path)) {
        } else {
            $this->createDirectory($this->path);
        }

        if ($this->isWriteable($this->path . '/' . $file) === false) {
            throw new LocalFilesystemException
            (ucfirst(strtolower($this->getFilesystemType()))
                . ' Filesystem:  file is not writable: ' . $this->path . '/' . $file);
        }

        if (file_exists($this->path . '/' . $file)) {

            if ($replace === false) {
                throw new LocalFilesystemException
                (ucfirst(strtolower($this->getFilesystemType()))
                    . ' Filesystem:  attempting to write to existing file: ' . $this->path . '/' . $file);
            }

            \unlink($this->path . '/' . $file);
        }

        try {
            \file_put_contents($this->path . '/' . $file, $data);

        } catch (Exception $e) {

            throw new LocalFilesystemException
            (ucfirst(strtolower($this->getFilesystemType()))
                . ' Filesystem:  error writing file ' . $this->path . '/' . $file);
        }

        return $this;
    }

    /**
     * Append data to file identified in path using the data value
     *
     * @param   string $data
     *
     * @return  $this
     * @since   1.0
     * @throws  LocalFilesystemException
     * @throws  LocalFilesystemException
     */
    private function append($data)
    {
        if ($this->exists === true) {
        } elseif ($this->is_file === false) {
        } else {
            throw new LocalFilesystemException
            (ucfirst(strtolower($this->getFilesystemType()))
                . ' Filesystem:  attempting to append to a folder, not a file ' . $this->path);
        }

        try {
            \file_put_contents($this->path, $data, FILE_APPEND);

        } catch (Exception $e) {

            throw new LocalFilesystemException
            (ucfirst(strtolower($this->getFilesystemType())) . ' Filesystem:  error appending to file '
                . $this->path);
        }

        return $this;
    }

    /**
     * Truncate file identified in path using the data value
     *
     * @return  $this
     * @since   1.0
     * @throws  LocalFilesystemException
     */
    private function truncate()
    {
        if ($this->exists === false) {
            throw new LocalFilesystemException
            (ucfirst(strtolower($this->getFilesystemType()))
                . ' Filesystem:  attempting to truncate file that does not exist. '
                . $this->path);
        }

        if ($this->is_file === true) {
        } else {
            throw new LocalFilesystemException
            (ucfirst(strtolower($this->getFilesystemType()))
                . ' Filesystem:  only a file can be truncated. ' . $this->path);
        }

        if ($this->isWriteable($this->path) === false) {
            throw new LocalFilesystemException
            (ucfirst(strtolower($this->getFilesystemType()))
                . ' Filesystem:  file is not writable and cannot be truncated: '
                . $this->path);
        }

        try {
            $fp = \fopen($this->path, "w");
            fclose($fp);

        } catch (Exception $e) {

            throw new LocalFilesystemException
            (ucfirst(strtolower($this->getFilesystemType())) . ' Filesystem:  error truncating file ' . $this->path);
        }

        return;
    }

    /**
     * Create Directory
     *
     * @param   bool $path
     *
     * @return  void
     * @since   1.0
     * @throws  LocalFilesystemException
     */
    protected function createDirectory($path)
    {
        if (file_exists($path)) {
            return;
        }
        try {
            mkdir($path, $this->default_directory_permissions, true);

        } catch (Exception $e) {

            throw new LocalFilesystemException
            ('Filesystem Create Directory: error creating directory: ' . $path);
        }

        return;
    }

    /**
     * Deletes the file identified in path.
     * Empty subdirectories are removed if $delete_subdirectories is true
     *
     * @param bool $delete_subdirectories default true
     *
     * @return void
     * @since   1.0
     * @throws  LocalFilesystemException
     * @throws  LocalFilesystemException
     */
    protected function delete($delete_subdirectories = true)
    {
        if ($this->is_root === true) {
            throw new LocalFilesystemException
            (ucfirst(
                strtolower($this->getFilesystemType())
            ) . ' Filesystem Delete: Request to delete root is not allowed'
                . $this->path);
        }

        if (file_exists($this->path)) {
        } else {

            throw new LocalFilesystemException
            (ucfirst(strtolower($this->getFilesystemType())) . ' Filesystem Delete: File not found ' . $this->path);
        }

        if ($this->is_writable === false) {

            throw new LocalFilesystemException
            (ucfirst(strtolower($this->getFilesystemType())) . ' Filesystem Delete: No write access to file/path: '
                . $this->path);
        }

        try {

            if (file_exists($this->path)) {
            } else {
                return;
            }

            $this->_deleteDiscoveryFilesArray();
            $this->_deleteDiscoveryDirectoriesArray();

        } catch (Exception $e) {

            throw new LocalFilesystemException
            (ucfirst(strtolower($this->getFilesystemType())) . ' Filesystem Delete: failed for: ' . $this->path);
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
        if (count($this->files) > 0 && is_array($this->files)) {
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
        if (count($this->directories) > 0 && is_array($this->directories)) {
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
     * @param   string $target_directory
     * @param   string $target_name
     * @param   bool   $replace
     * @param   string $target_filesystem_type
     *
     * @return  $this
     * @since   1.0
     */
    protected function copy($target_directory, $target_name = '', $replace = true, $target_filesystem_type = '')
    {
        if ($target_filesystem_type == '') {
            $target_filesystem_type = $this->getFilesystemType();
        }

        $this->moveOrCopy($target_directory, $target_name, $replace, $target_filesystem_type, 'copy');

        return $this;
    }

    /**
     * Moves the file identified in path to the location identified in the new_parent_name_directory
     *  replacing existing contents, if indicated, and creating directories needed, if indicated
     *
     * @param   string $target_directory
     * @param   string $target_name
     * @param   bool   $replace
     * @param   string $target_filesystem_type
     *
     * @return  $this
     * @since   1.0
     */
    protected function move($target_directory, $target_name = '', $replace = true, $target_filesystem_type = '')
    {
        if ($target_filesystem_type == '') {
            $target_filesystem_type = $this->getFilesystemType();
        }

        $this->moveOrCopy($target_directory, $target_name, $replace, $target_filesystem_type, 'move');

        return $this;
    }

    /**
     * Copies the file identified in $path to the $target_directory for the $target_filesystem_type adapter
     *  replacing existing contents and creating directories needed, if indicated
     *
     * Note: $target_filesystem_type is an instance of the Filesystem
     *
     * @param   string $target_directory
     * @param   string $target_name
     * @param   bool   $replace
     * @param   string $target_filesystem_type
     * @param   string $move_or_copy
     *
     * @return  $this
     * @since   1.0
     * @throws  LocalFilesystemException
     */
    protected function moveOrCopy
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
                throw new LocalFilesystemException
                (ucfirst(strtolower($this->getFilesystemType())) . ' Filesystem ' . $move_or_copy
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
            throw new LocalFilesystemException
            (ucfirst(strtolower($this->getFilesystemType())) . ' Filesystem moveOrCopy: failed.'
                . 'This path does not exist: '
                . $this->path . ' Specified as source for ' . $move_or_copy
                . ' operation to ' . $target_directory);
        }

        if (file_exists($target_directory)) {
        } else {
            throw new LocalFilesystemException
            (ucfirst(strtolower($this->getFilesystemType())) . ' Filesystem moveOrCopy: failed. '
                . ' This path does not exist: '
                . $this->path . ' Specified as destination for ' . $move_or_copy
                . ' to ' . $target_directory);
        }

        if (is_writeable($target_directory) === false) {
            throw new LocalFilesystemException
            (ucfirst(strtolower($this->getFilesystemType()))
                . ' Filesystem Delete: No write access to file/path: ' . $target_directory);
        }

        if ($move_or_copy == 'move') {
            if (is_writeable($this->path) === false) {
                throw new LocalFilesystemException
                (ucfirst(strtolower($this->getFilesystemType()))
                    . ' Filesystem Delete: No write access for moving source file/path: '
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
        if (count($this->directories) > 0 && is_array($this->directories)) {

            asort($this->directories);

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
            }
        }

        /** Copy files now that directories are in place */
        if (count($this->files) > 0 && is_array($this->files)) {

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
            }
        }

        /** For move, remove the files and folders just copied */
        if ($move_or_copy == 'move') {
            $this->_deleteDiscoveryFilesArray();
            $this->_deleteDiscoveryDirectoriesArray();
        }

        return $this;
    }

    /**
     * Convert the path into a path relative to the path passed in
     *
     * @param   string $relative_to_this_path
     *
     * @return  $this
     * @throws  LocalFilesystemException
     * @since   1.0
     */
    protected function getRelativePath($relative_to_this_path = '')
    {
        $hold   = getcwd();
        $target = $this->normalise($relative_to_this_path);
        chdir($target);
        $this->data = realpath($this->path);
        chdir($hold);

        return $this;
    }

    /**
     * Changes the owner for the file or folder identified in path
     *
     * @param   string $user_name
     *
     * @return  $this
     * @throws  LocalFilesystemException
     * @since   1.0
     */
    protected function changeOwner($user_name)
    {
        try {
            chown($this->path, $user_name);

        } catch (Exception $e) {

            throw new LocalFilesystemException
            (ucfirst(strtolower($this->getFilesystemType()))
                . ' Filesystem: changeOwner method failed for Path: ' . $this->path
                . ' Owner: ' . $user_name);
        }

        return $this;
        /**
        // Check the result
        $stat = stat($path);
        print_r(posix_getpwuid($stat['uid']));
         */
    }

    /**
     * Changes the group for the file or folder identified in path
     *
     * @param   int $group_id
     *
     * @return  $this
     * @throws  LocalFilesystemException
     * @since   1.0
     */
    protected function changeGroup($group_id)
    {
        try {
            chgrp($this->path, $group_id);

        } catch (Exception $e) {

            throw new LocalFilesystemException
            (ucfirst(strtolower($this->getFilesystemType()))
                . ' Filesystem: changeGroup method failed for Path: ' . $this->path
                . ' Group: ' . $group_id);
        }

        return $this;
    }

    /**
     * Change the file mode for user for read, write, execute access
     *
     * @param   int $mode
     *
     * @return  $this
     * @throws  LocalFilesystemException
     * @since   1.0
     */
    protected function changePermission($mode)
    {
        try {
            chmod($this->path, octdec(str_pad($mode, 4, '0', STR_PAD_LEFT)));

            if ($mode === octdec(substr(sprintf('%o', fileperms($this->path)), - 4))) {
            } else {
                throw new Exception
                ('File Permissions did not change to ' . $mode);
            }

        } catch (Exception $e) {

            throw new LocalFilesystemException
            (ucfirst(strtolower($this->getFilesystemType()))
                . ' Filesystem: changePermission method failed for Path: ' . $this->path
                . ' Permissions: ' . octdec(str_pad($mode, 4, '0', STR_PAD_LEFT)));
        }
    }

    /**
     * Update the touch time and/or the access time for the directory or file identified in the path
     *
     * @param   null|int $modification_time
     * @param   null|int $access_time
     *
     * @return  $this
     * @throws  LocalFilesystemException
     * @since   1.0
     */
    protected function touch($modification_time = null, $access_time = null)
    {
        if ($modification_time == '' || $modification_time === null || $modification_time == 0) {
            $modification_time = null;
        }

        if ($access_time == '' || $access_time === null || $access_time == 0) {
            $access_time = null;
        }

        try {
            touch($this->path, $modification_time, $access_time);

            $hold = stat($this->path);

            if ($modification_time == $hold['mtime']) {
            } else {
                throw new LocalFilesystemException ('Local Filesystem: Touch Failed.');
            }

        } catch (Exception $e) {

            throw new LocalFilesystemException

            (ucfirst(strtolower($this->getFilesystemType()))
                . ' Filesystem: touch method failed for Path: ' . $this->path . ' ' . $e->getMessage());
        }

        return $this;
    }

    /**
     * Destruct Method
     *
     * @return  $this
     * @since   1.0
     */
    public function __destruct()
    {
        if (is_resource($this->connection)) {
            $this->close();
        }

        return $this;
    }

    /**
     * Close the Ftp Connection
     *
     * @return  $this
     * @since   1.0
     * @throws  \Exception
     */
    protected function close()
    {
        if ($this->is_connected === true) {
            try {
                ftp_close($this->connection);

            } catch (LocalFilesystemException $e) {

                throw new \Exception
                ('Filesystem Adapter Ftp: Closing Ftp Connection Failed');
            }
        }

        return $this;
    }

    /**
     * Discovery of folders and files for specified path
     *
     * @since   1.0
     * @return  $this
     */
    protected function discovery()
    {
        $this->directories = array();
        $this->files       = array();

        if ($this->exists === true) {
        } else {
            return $this;
        }

        if ($this->is_file === true) {
            $this->files[] = $this->path;
        }

        if (is_dir($this->path)) {
        } else {
            return $this;
        }

        if (count(glob($this->path . '/*')) == 0) {
            return $this;
        }

        $this->directories[] = $this->path;

        $objects = new RecursiveIteratorIterator (
            new RecursiveDirectoryIterator($this->path),
            RecursiveIteratorIterator::SELF_FIRST);

        if (is_object($objects)) {
        } else {
            return $this;
        }

        foreach ($objects as $name => $object) {

            if (is_file($name)) {
                $this->files[] = $name;

            } elseif (is_dir($name)) {

                if (basename($name) == '.' || basename($name) == '..') {
                } else {
                    $this->directories[] = $name;
                }
            }
        }

        return $this;
    }
}
