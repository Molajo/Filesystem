<?php
/**
 * Filesystem Abstract Type
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Filesystem\Type;

defined('MOLAJO') or die;

use DateTime;
use DateTimeZone;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

use Molajo\Filesystem\Adapter as fsAdapter;
use Molajo\Filesystem\Adapter\AdapterInterface;
use Molajo\Filesystem\Adapter\ActionsInterface;
use Molajo\Filesystem\Adapter\MetadataInterface;
use Molajo\Filesystem\Adapter\SystemInterface;

use Exception;
use Molajo\Filesystem\Exception\FilesystemException;

/**
 * Filesystem Abstract Type
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class AbstractType
{
    /**
     * ADAPTER PROPERTIES
     *
     * Path
     *
     * @var    string
     * @since  1.0
     */
    protected $path;

    /**
     * Options
     *
     * @var    string
     * @since  1.0
     */
    protected $options;

    /**
     * SYSTEM PROPERTIES
     *
     * Filesystem Type
     *
     * @var    string
     * @since  1.0
     */
    protected $filesystem_type;

    /**
     * Root Directory for Filesystem
     *
     * @var    string
     * @since  1.0
     */
    protected $root;

    /**
     * Is path root?
     *
     * @var    string
     * @since  1.0
     */
    protected $is_root;

    /**
     * Persistence (Permanent 1, Temporary 0)
     *
     * @var    bool
     * @since  1.0
     */
    protected $persistence;

    /**
     * Directory Permissions
     *
     * @var    int
     * @since  1.0
     */
    protected $default_directory_permissions;

    /**
     * File Permissions
     *
     * @var    int
     * @since  1.0
     */
    protected $default_file_permissions;

    /**
     * Read only
     *
     * @var    bool
     * @since  1.0
     */
    protected $read_only;

    /**
     * LOGON
     *
     * Username
     *
     * @var    string
     * @since  1.0
     */
    protected $username;

    /**
     * Password
     *
     * @var    string
     * @since  1.0
     */
    protected $password;

    /**
     * Host
     *
     * @var    string
     * @since  1.0
     */
    protected $host;

    /**
     * Port
     *
     * @var    string
     * @since  1.0
     */
    protected $port = 21;

    /**
     * Connection Type
     *
     * @var    string
     * @since  1.0
     */
    protected $connection_type;

    /**
     * Timeout in minutes
     *
     * @var    string
     * @since  1.0
     */
    protected $timeout = 15;

    /**
     * Passive Mode
     *
     * @var    bool
     * @since  1.0
     */
    protected $passive_mode = false;

    /**
     * Initial Directory after Connection
     *
     * @var    object|resource
     * @since  1.0
     */
    protected $initial_directory;

    /**
     * FTP Mode - FTP_ASCII or FTP_BINARY
     *
     * @var    bool
     * @since  1.0
     */
    protected $ftp_mode = FTP_ASCII;

    /**
     * Connection
     *
     * @var    object|resource
     * @since  1.0
     */
    protected $connection;

    /**
     * Is Connected?
     *
     * @var    bool
     * @since  1.0
     */
    protected $is_connected;

    /**
     * Timezone
     *
     * @var    DateTimeZone
     * @since  1.0
     */
    protected $timezone;

    /**
     * WORKING PROPERTIES
     *
     * Directories
     *
     * @var    array
     * @since  1.0
     */
    protected $directories;

    /**
     * Files
     *
     * @var    array
     * @since  1.0
     */
    protected $files;

    /**
     * METADATA PROPERTIES
     *
     * Exists
     *
     * @var    bool
     * @since  1.0
     */
    protected $exists;

    /**
     * Relative Path for Filesystem
     *
     * @var    string
     * @since  1.0
     */
    protected $relative_path;

    /**
     * Absolute Path for Filesystem
     *
     * @var    string
     * @since  1.0
     */
    protected $absolute_path;

    /**
     * Is Absolute Path
     *
     * @var    bool
     * @since  1.0
     */
    protected $is_absolute_path;

    /**
     * Is Directory
     *
     * @var    bool
     * @since  1.0
     */
    protected $is_directory;

    /**
     * Is File
     *
     * @var    bool
     * @since  1.0
     */
    protected $is_file;

    /**
     * Is Link
     *
     * @var    bool
     * @since  1.0
     */
    protected $is_link;

    /**
     * Type: Directory, File, Link
     *
     * @var    string
     * @since  1.0
     */
    protected $type;

    /**
     * File name
     *
     * @var    string
     * @since  1.0
     */
    protected $name;

    /**
     * Parent
     *
     * @var    string
     * @since  1.0
     */
    protected $parent;

    /**
     * Extension
     *
     * @var    string
     * @since  1.0
     */
    protected $extension;

    /**
     * Filename without Extension
     *
     * @var    string
     * @since  1.0
     */
    protected $no_extension;

    /**
     * Size
     *
     * @var    int
     * @since  1.0
     */
    protected $size;

    /**
     * Mime Type
     *
     * @var    string
     * @since  1.0
     */
    protected $mime_type;

    /**
     * Owner
     *
     * @var    string
     * @since  1.0
     */
    protected $owner;

    /**
     * Group
     *
     * @var    string
     * @since  1.0
     */
    protected $group;

    /**
     * Create Date
     *
     * @var    Datetime
     * @since  1.0
     */
    protected $create_date;

    /**
     * Modified Date
     *
     * @var    Datetime
     * @since  1.0
     */
    protected $modified_date;

    /**
     * Access Date
     *
     * @var    Datetime
     * @since  1.0
     */
    protected $access_date;

    /**
     * Is Readable
     *
     * @var    bool
     * @since  1.0
     */
    protected $is_readable;

    /**
     * Is Writable
     *
     * @var    bool
     * @since  1.0
     */
    protected $is_writable;

    /**
     * Is Executable
     *
     * @var    bool
     * @since  1.0
     */
    protected $is_executable;

    /**
     * Hash File Sha1
     *
     * @var    string
     * @since  1.0
     */
    protected $hash_file_sha1;

    /**
     * Hash File MD5
     *
     * @var    string
     * @since  1.0
     */
    protected $hash_file_md5;

    /**
     * Hash File Sha1 20
     *
     * @var    string
     * @since  1.0
     */
    protected $hash_file_sha1_20;

    /**
     * Action Results
     *
     * @var     mixed
     * @since   1.0
     */
    protected $data;

    /**
     * class constructor
     *
     * @since   1.0
     * @throws  FilesystemException
     */
    public function __construct($filesystem_type)
    {

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
        $this->setRoot();
        $this->setPersistence();
        $this->setDirectoryDefaultPermissions();
        $this->setFileDefaultPermissions();
        $this->setReadOnly();

        return $this;
    }

    /**
     * Adapter Interface Step 2:
     * Set the Path
     *
     * @param   string $path
     *
     * @return  string
     * @since   1.0
     */
    protected function setPath($path)
    {
        $this->path = $path;

        return $this->path;
    }

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
        $this->setPersistence();
        $this->setDirectoryDefaultPermissions();
        $this->setFileDefaultPermissions();
        $this->setReadOnly();

        return $this;
    }

    /**
     * Set Filesystem Type
     *
     * @param   string $filesystem_type
     *
     * @return  $this
     * @since   1.0
     */
    protected function setFilesystemType($filesystem_type)
    {
        $this->filesystem_type = $filesystem_type;

        return $this;
    }

    /**
     * Set Filesystem Type
     *
     * @return  string
     * @since   1.0
     */
    protected function getFilesystemType()
    {
        return $this->filesystem_type;
    }

    /**
     * Set Root of Filesystem
     *
     * @return  $this
     * @since   1.0
     * @throws  FilesystemException
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

        throw new FilesystemException
        ('Filesystem Local: Root is not a valid directory. ' . $root);
    }

    /**
     * Set persistence indicator for Filesystem
     *
     * @return  $this
     * @since   1.0
     */
    protected function setPersistence()
    {
        $persistence = true;

        if (isset($this->options['persistence'])) {
            $persistence = $this->options['persistence'];
        }

        if ($persistence === false) {
        } else {
            $persistence = true;
        }

        $persistence = $this->setTorF($persistence, true);

        $this->persistence = $persistence;

        return $this;
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
     * Get Persistence indicator
     *
     * @return  bool
     * @since   1.0
     */
    protected function getPersistence()
    {
        return $this->persistence;
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
     * Adapter Interface Step 3:
     *
     * Retrieves and sets metadata for the file specified in path
     *
     * @return  $this
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
        $this->hashFileSha1_20();

        /**
         *  Discovery creates an array of Files and Directories based on Path
         */
        if ($this->exists === true) {
            $this->discovery($this->path);
        }

        $this->getSize();

        return $this;
    }

    /**
     * Does the path exist (as either a file or a folder)?
     *
     * @return $this
     * @since  1.0
     */
    protected function exists()
    {
        $this->exists = false;

        if (file_exists($this->path)) {
            $this->exists = true;
        }

        return $this;
    }

    /**
     * Retrieves the absolute path, which is the relative path from the root directory,
     *  prepended with a '/'.
     *
     * @return  $this
     * @since   1.0
     * @throws  FilesystemException
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
     * @throws  FilesystemException
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
     * @throws  FilesystemException
     * @throws  FilesystemException
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

            throw new FilesystemException
            ('Not a directory, file or a link.');
        }

        return $this->type;
    }

    /**
     * Get Parent
     *
     * @return  null|string
     * @since   1.0
     * @throws  FilesystemException
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
     * @throws  FilesystemException
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
     * @throws  FilesystemException
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
            throw new FilesystemException
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
     * @throws  FilesystemException
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
            throw new FilesystemException
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
     * @throws  FilesystemException
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
            throw new FilesystemException
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
     * @throws  FilesystemException
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

            throw new FilesystemException
            (ucfirst(strtolower($this->getFilesystemType())) . ' Filesystem getCreateDate failed for ' . $this->path);
        }

        return $this->create_date;
    }

    /**
     * Retrieves Last Access Date for directory or file identified in the path
     *
     * @return  null|string
     * @since   1.0
     * @throws  FilesystemException
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

            throw new FilesystemException
            (ucfirst(strtolower($this->getFilesystemType())) . ' Filesystem getAccessDate failed for ' . $this->path);
        }

        return $this->access_date;
    }

    /**
     * Retrieves Last Update Date for directory or file identified in the path
     *
     * @return  null|string
     * @since   1.0
     * @throws  FilesystemException
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
            throw new FilesystemException

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
                    throw new FilesystemException
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
                    throw new FilesystemException
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
                    throw new FilesystemException
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
                throw new FilesystemException
                ($this->getFilesystemType() . ' Filesystem doAction Method ' . $action . ' does not exist.');
        }

        return;
    }

    /**
     *  ActionsInterface
     */

    /**
     * Returns the contents of the file identified by path
     *
     * @return  mixed
     * @since   1.0
     * @throws  FilesystemException when file does not exist
     */
    protected function read()
    {
        if ($this->exists === false) {
            throw new FilesystemException
            (ucfirst(strtolower($this->getFilesystemType())) . ' Filesystem Read: File does not exist: ' . $this->path);
        }

        if ($this->is_file === false) {
            throw new FilesystemException
            (ucfirst(strtolower($this->getFilesystemType())) . ' Filesystem Read: Is not a file: ' . $this->path);
        }

        if ($this->is_readable === false) {
            throw new FilesystemException
            (ucfirst(
                strtolower($this->getFilesystemType())
            ) . ' Filesystem Read: Not permitted to read: ' . $this->path);
        }

        try {
            $this->data = file_get_contents($this->path);

        } catch (\Exception $e) {

            throw new FilesystemException
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
     * @throws  FilesystemException
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
            throw new FilesystemException
            (ucfirst(
                strtolower($this->getFilesystemType())
            ) . ' Filesystem Write: must be directory or file: ' . $this->path . '/' . $file);
        }

        if (trim($data) == '' || strlen($data) == 0) {
            if ($this->is_file === true) {

                throw new FilesystemException
                (ucfirst(strtolower($this->getFilesystemType()))
                    . ' Filesystem:  attempting to write no data to file: ' . $this->path . '/' . $file);
            }
        }

        if (trim($data) == '' || strlen($data) == 0) {
            if ($file == '') {
                throw new FilesystemException
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
            throw new FilesystemException
            (ucfirst(strtolower($this->getFilesystemType()))
                . ' Filesystem:  file is not writable: ' . $this->path . '/' . $file);
        }

        if (file_exists($this->path . '/' . $file)) {

            if ($replace === false) {
                throw new FilesystemException
                (ucfirst(strtolower($this->getFilesystemType()))
                    . ' Filesystem:  attempting to write to existing file: ' . $this->path . '/' . $file);
            }

            \unlink($this->path . '/' . $file);
        }

        try {
            \file_put_contents($this->path . '/' . $file, $data);

        } catch (Exception $e) {

            throw new FilesystemException
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
     * @throws  FilesystemException
     * @throws  FilesystemException
     */
    private function append($data)
    {
        if ($this->exists === true) {
        } elseif ($this->is_file === false) {
        } else {
            throw new FilesystemException
            (ucfirst(strtolower($this->getFilesystemType()))
                . ' Filesystem:  attempting to append to a folder, not a file ' . $this->path);
        }

        try {
            \file_put_contents($this->path, $data, FILE_APPEND);

        } catch (Exception $e) {

            throw new FilesystemException
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
     * @throws  FilesystemException
     */
    private function truncate()
    {
        if ($this->exists === false) {
            throw new FilesystemException
            (ucfirst(strtolower($this->getFilesystemType()))
                . ' Filesystem:  attempting to truncate file that does not exist. '
                . $this->path);
        }

        if ($this->is_file === true) {
        } else {
            throw new FilesystemException
            (ucfirst(strtolower($this->getFilesystemType()))
                . ' Filesystem:  only a file can be truncated. ' . $this->path);
        }

        if ($this->isWriteable($this->path) === false) {
            throw new FilesystemException
            (ucfirst(strtolower($this->getFilesystemType()))
                . ' Filesystem:  file is not writable and cannot be truncated: '
                . $this->path);
        }

        try {
            $fp = \fopen($this->path, "w");
            fclose($fp);

        } catch (Exception $e) {

            throw new FilesystemException
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
     * @param bool $delete_subdirectories default true
     *
     * @return void
     * @since   1.0
     * @throws  FilesystemException
     * @throws  FilesystemException
     */
    protected function delete($delete_subdirectories = true)
    {
        if ($this->is_root === true) {
            throw new FilesystemException
            (ucfirst(
                strtolower($this->getFilesystemType())
            ) . ' Filesystem Delete: Request to delete root is not allowed'
                . $this->path);
        }

        if (file_exists($this->path)) {
        } else {

            throw new FilesystemException
            (ucfirst(strtolower($this->getFilesystemType())) . ' Filesystem Delete: File not found ' . $this->path);
        }

        if ($this->is_writable === false) {

            throw new FilesystemException
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

            throw new FilesystemException
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
     * @throws  FilesystemException
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
                throw new FilesystemException
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
            throw new FilesystemException
            (ucfirst(strtolower($this->getFilesystemType())) . ' Filesystem moveOrCopy: failed.'
                . 'This path does not exist: '
                . $this->path . ' Specified as source for ' . $move_or_copy
                . ' operation to ' . $target_directory);
        }

        if (file_exists($target_directory)) {
        } else {
            throw new FilesystemException
            (ucfirst(strtolower($this->getFilesystemType())) . ' Filesystem moveOrCopy: failed. '
                . ' This path does not exist: '
                . $this->path . ' Specified as destination for ' . $move_or_copy
                . ' to ' . $target_directory);
        }

        if (is_writeable($target_directory) === false) {
            throw new FilesystemException
            (ucfirst(strtolower($this->getFilesystemType()))
                . ' Filesystem Delete: No write access to file/path: ' . $target_directory);
        }

        if ($move_or_copy == 'move') {
            if (is_writeable($this->path) === false) {
                throw new FilesystemException
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
     * @throws  FilesystemException
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
     * @throws  FilesystemException
     * @since   1.0
     */
    protected function changeOwner($user_name)
    {
        try {
            chown($this->path, $user_name);

        } catch (Exception $e) {

            throw new FilesystemException
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
     * @throws  FilesystemException
     * @since   1.0
     */
    protected function changeGroup($group_id)
    {
        try {
            chgrp($this->path, $group_id);

        } catch (Exception $e) {

            throw new FilesystemException
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
     * @throws  FilesystemException
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

            throw new FilesystemException
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
     * @throws  FilesystemException
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
                throw new FilesystemException ('Filesystem: Touch Failed.');
            }

        } catch (Exception $e) {

            throw new FilesystemException

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

            } catch (\Exception $e) {

                throw new \Exception
                ('Filesystem Adapter Ftp: Closing Ftp Connection Failed');
            }
        }

        return $this;
    }

    /**
     * Get Date Time
     *
     * @param  string $time
     *
     * @return DateTime
     * @since  1.0
     */
    protected function getDateTime($time)
    {
        if ($time instanceof DateTime) {
            return $time;
        }

        if (is_int($time) || is_float($time)) {
        } else {
            $time = null;
        }

        //todo test and remove @
        return new DateTime('@' . intval($time), $this->timezone);
    }

    /**
     * Normalizes the given path
     *
     * @param   $path
     *
     * @return  string
     * @since   1.0
     */
    protected function normalise($path = '')
    {
        if ($path == '') {
            $this->path = $path;
        }

        /** Unescape slashes */
        $path = str_replace('\\', '/', $path);


        /** Determine if it is absolute path */
        $absolute_path = false;
        if (substr($path, 0, 1) == '/') {
            $absolute_path = true;
            $path          = substr($path, 1, strlen($path));
        }

        /**  Filter: empty values
         *
         * @link http://tinyurl.com/arrayFilterStrlen
         */
        $nodes = array_filter(explode('/', $path), 'strlen');

        $normalised = array();

        foreach ($nodes as $node) {
            if ($node == '.' || $node == '..') {
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

    /**
     * Common method for creating new path for copy or move
     *
     * @param   string $path             (file or folder)
     * @param   string $target_directory
     * @param   string $base_folder
     *
     * @since   1.0
     * @return  string
     */
    protected function build_new_path($path, $target_directory, $base_folder)
    {
        if ($base_folder == $path
            || $target_directory == $base_folder
        ) {
            $temp = $target_directory;
        } else {
            $temp = $target_directory . substr($path, strlen($base_folder), 99999);
        }

        return $temp;
    }

    /**
     * Utility method - force consistency in True and False
     *
     * @param   bool $variable
     * @param   bool $default
     *
     * @return  bool
     * @since   1.0
     */
    protected function setTorF($variable, $default = false)
    {
        if ($default === true) {

            if ($variable === false) {
            } else {
                $variable = true;
            }

        } else {
            if ($variable === true) {
            } else {
                $variable = false;
            }
        }

        return $variable;
    }

    /**
     * Utility method - force consistency in True and False
     *
     * @param   bool $variable
     * @param   bool $default
     *
     * @return  bool
     * @since   1.0
     */
    protected function getMimeArray()
    {
        $mime_types = array(

            'txt'  => 'text/plain',
            'htm'  => 'text/html',
            'html' => 'text/html',
            'php'  => 'text/html',
            'css'  => 'text/css',
            'js'   => 'application/javascript',
            'json' => 'application/json',
            'xml'  => 'application/xml',
            'swf'  => 'application/x-shockwave-flash',
            'flv'  => 'video/x-flv',
            'png'  => 'image/png',
            'jpe'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg'  => 'image/jpeg',
            'gif'  => 'image/gif',
            'bmp'  => 'image/bmp',
            'ico'  => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif'  => 'image/tiff',
            'svg'  => 'image/svg+xml',
            'svgz' => 'image/svg+xml',
            'zip'  => 'application/zip',
            'rar'  => 'application/x-rar-compressed',
            'exe'  => 'application/x-msdownload',
            'msi'  => 'application/x-msdownload',
            'cab'  => 'application/vnd.ms-cab-compressed',
            'mp3'  => 'audio/mpeg',
            'qt'   => 'video/quicktime',
            'mov'  => 'video/quicktime',
            'pdf'  => 'application/pdf',
            'psd'  => 'image/vnd.adobe.photoshop',
            'ai'   => 'application/postscript',
            'eps'  => 'application/postscript',
            'ps'   => 'application/postscript',
            'doc'  => 'application/msword',
            'rtf'  => 'application/rtf',
            'xls'  => 'application/vnd.ms-excel',
            'ppt'  => 'application/vnd.ms-powerpoint',
            'docx' => 'application/msword',
            'xlsx' => 'application/vnd.ms-excel',
            'pptx' => 'application/vnd.ms-powerpoint',
            'odt'  => 'application/vnd.oasis.opendocument.text',
            'ods'  => 'application/vnd.oasis.opendocument.spreadsheet',
        );

        return $mime_types[$this->extension];
    }
}
