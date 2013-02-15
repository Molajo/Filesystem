<?php
/**
 * Filesystem Type Abstract Class
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
use RuntimeException;
use Molajo\Filesystem\Exception\FilesystemException;
use Molajo\Filesystem\Exception\NotFoundException;

/**
 * FilesystemType Abstract Class
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class FilesystemType implements AdapterInterface, ActionsInterface, MetadataInterface, SystemInterface
{
    /**
     * ADAPTER PROPERTIES
     *
     * Path
     *
     * @var    string
     * @since  1.0
     */
    public $path;

    /**
     * Options
     *
     * @var    string
     * @since  1.0
     */
    public $options;

    /**
     * SYSTEM PROPERTIES
     *
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
     * Is path root?
     *
     * @var    string
     * @since  1.0
     */
    public $is_root;

    /**
     * Persistence (Permanent 1, Temporary 0)
     *
     * @var    bool
     * @since  1.0
     */
    public $persistence;

    /**
     * Directory Permissions
     *
     * @var    int
     * @since  1.0
     */
    public $default_directory_permissions;

    /**
     * File Permissions
     *
     * @var    int
     * @since  1.0
     */
    public $default_file_permissions;

    /**
     * Read only
     *
     * @var    bool
     * @since  1.0
     */
    public $read_only;

    /**
     * LOGON
     *
     * Username
     *
     * @var    string
     * @since  1.0
     */
    public $username;

    /**
     * Password
     *
     * @var    string
     * @since  1.0
     */
    public $password;

    /**
     * Host
     *
     * @var    string
     * @since  1.0
     */
    public $host;

    /**
     * Port
     *
     * @var    string
     * @since  1.0
     */
    public $port = 21;

    /**
     * Connection Type
     *
     * @var    string
     * @since  1.0
     */
    public $connection_type;

    /**
     * Timeout in minutes
     *
     * @var    string
     * @since  1.0
     */
    public $timeout = 15;

    /**
     * Passive Mode
     *
     * @var    bool
     * @since  1.0
     */
    public $passive_mode = false;

    /**
     * Initial Directory after Connection
     *
     * @var    object|resource
     * @since  1.0
     */
    public $initial_directory;

    /**
     * Connection
     *
     * @var    object|resource
     * @since  1.0
     */
    public $connection;

    /**
     * Is Connected?
     *
     * @var    bool
     * @since  1.0
     */
    public $is_connected;

    /**
     * Timezone
     *
     * @var    bool
     * @since  1.0
     */
    public $timezone;

    /**
     * WORKING PROPERTIES
     *
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
     * METADATA PROPERTIES
     *
     * Exists
     *
     * @var    bool
     * @since  1.0
     */
    public $exists;

    /**
     * Relative Path for Filesystem
     *
     * @var    string
     * @since  1.0
     */
    public $relative_path;

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
    public $is_absolute_path;

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
     * Filename without Extension
     *
     * @var    string
     * @since  1.0
     */
    public $no_extension;

    /**
     * Size
     *
     * @var    int
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
     * Modified Date
     *
     * @var    Datetime
     * @since  1.0
     */
    public $modified_date;

    /**
     * Access Date
     *
     * @var    Datetime
     * @since  1.0
     */
    public $access_date;

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
     * Hash File Sha1
     *
     * @var    string
     * @since  1.0
     */
    public $hash_file_sha1;

    /**
     * Hash File MD5
     *
     * @var    string
     * @since  1.0
     */
    public $hash_file_md5;

    /**
     * Hash File Sha1 20
     *
     * @var    string
     * @since  1.0
     */
    public $hash_file_sha1_20;

    /**
     * Action Results
     *
     * @var     mixed
     * @since   1.0
     */
    public $data;

    /**
     * Class constructor
     *
     * @since   1.0
     * @throws  FilesystemException
     */
    public function __construct()
    {

    }

    /**
     * Adapter Interface Step 1:
     *
     * Method to connect to a Local server
     *
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
     * @param   array  $options
     *
     * @return  void
     * @since   1.0
     */
    public function setOptions($options = array())
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
        $this->setConnectionType();
        $this->setTimeout();
        $this->setPassiveMode();
        $this->setInitialDirectory();

        $this->setRoot();
        $this->setPersistence();
        $this->setDirectoryDefaultPermissions();
        $this->setFileDefaultPermissions();
        $this->setReadOnly();

        return;
    }

    /**
     * Set Filesystem Type
     *
     * @param   string  $filesystem_type
     *
     * @return  void
     * @since   1.0
     */
    public function setFilesystemType($filesystem_type)
    {
        $this->filesystem_type = $filesystem_type;

        return;
    }

    /**
     * Set Filesystem Type
     *
     * @return  string
     * @since   1.0
     */
    public function getFilesystemType()
    {
        return $this->filesystem_type;
    }

    /**
     * Set Root of Filesystem
     *
     * @return  void
     * @since   1.0
     * @throws  FilesystemException
     */
    public function setRoot()
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
                return;
            }
        }

        throw new FilesystemException
        ('Filesystem Local: Root is not a valid directory. ' . $root);
    }

    /**
     * Set persistence indicator for Filesystem
     *
     * @return  void
     * @since   1.0
     */
    public function setPersistence()
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

        return;
    }

    /**
     * Set Directory Permissions for Filesystem
     *
     * @return  void
     * @since   1.0
     */
    public function setDirectoryDefaultPermissions()
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

        return;
    }

    /**
     * Set Default Filesystem Permissions for Files
     *
     * @return  void
     * @since   1.0
     */
    public function setFileDefaultPermissions()
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

        return;
    }

    /**
     * Set Read Only Setting for Filesystem
     *
     * @return  void
     * @since   1.0
     */
    public function setReadOnly()
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

        return;
    }

    /**
     * Set Initial Directory for the System Connection
     *
     * @return  void
     * @since   1.0
     */
    public function setInitialDirectory()
    {
        $initial_directory = null;

        if (isset($this->options['initial_directory'])) {
            $initial_directory = $this->options['initial_directory'];
        }

        if ($initial_directory === null) {
        } else {
            $this->initial_directory = $initial_directory;
        }

        return;
    }

    /**
     * Get Initial Directory
     *
     * @return  string
     * @since   1.0
     */
    public function getInitialDirectory()
    {
        return $this->initial_directory;
    }

    /**
     * Set Timezone
     *
     * @return  void
     * @since   1.0
     */
    public function setTimezone()
    {
        if (isset($this->options['timezone'])) {
            $this->timezone = $this->options['timezone'];
        } else {
            $this->timezone = 'GMT';
        }

        return;
    }

    /**
     * Get Timezone
     *
     * @return  string
     * @since   1.0
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * Set Connection
     *
     * @param   resource  $connection
     *
     * @return  void
     * @since   1.0
     */
    public function setConnection($connection)
    {
        $this->connection = $connection;

        $this->is_connected = false;

        if ($this->connection === null || $this->connection === false) {
        } else {
            $this->is_connected = true;
        }

        $this->is_connected = $this->setTorF($this->is_connected, true);

        return;
    }

    /**
     * get Connection
     *
     * @return  mixed
     * @since   1.0
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Set Connection
     *
     * @return  void
     * @since   1.0
     */
    public function setIsConnected()
    {
        $this->is_connected = false;

        if ($this->connection === null || $this->connection === false) {
        } else {
            $this->is_connected = true;
        }

        $this->is_connected = $this->setTorF($this->is_connected, true);

        return;
    }

    /**
     * get IsConnected
     *
     * @return  mixed
     * @since   1.0
     */
    public function getIsConnected()
    {
        return $this->is_connected;
    }

    /**
     * Get Root of Filesystem
     *
     * @return  string
     * @since   1.0
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * Get Persistence indicator
     *
     * @return  bool
     * @since   1.0
     */
    public function getPersistence()
    {
        return $this->persistence;
    }

    /**
     * Get Directory Permissions for Filesystem
     *
     * @return  string
     * @since   1.0
     */
    public function getDirectoryDefaultPermissions()
    {
        return $this->default_directory_permissions;
    }

    /**
     * Get File Permissions for Filesystem
     *
     * @return  int
     * @since   1.0
     */
    public function getFileDefaultPermissions()
    {
        return $this->default_file_permissions;
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
     * Set the username
     *
     * @return  void
     * @since   1.0
     */
    public function setUsername()
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
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set the password
     *
     * @return  void
     * @since   1.0
     */
    public function setPassword()
    {
        $password = null;

        if (isset($this->options['password'])) {
            $password = $this->options['password'];
        }

        $this->password = $password;

        return;
    }

    /**
     * Get the password
     *
     * @return  mixed
     * @since   1.0
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the Host
     *
     * @return  void
     * @since   1.0
     */
    public function setHost()
    {
        $host = null;

        if (isset($this->options['host'])) {
            $host = $this->options['host'];
        }

        $this->host = $host;

        return;
    }

    /**
     * Get the Host
     *
     * @return  mixed
     * @since   1.0
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Set the Port
     *
     * @return  int
     * @since   1.0
     */
    public function setPort()
    {
        $port = null;

        if (isset($this->options['port'])) {
            $port = $this->options['port'];
        }

        $this->port = $port;

        return;
    }

    /**
     * Get the Port
     *
     * @return  mixed
     * @since   1.0
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Set the Port
     *
     * @return  int
     * @since   1.0
     */
    public function setConnectionType()
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

        return;
    }

    /**
     * Get the Connection Type
     *
     * @return  mixed
     * @since   1.0
     */
    public function getConnectionType()
    {
        return $this->connection_type;
    }

    /**
     * Set the Timeout (default 900 seconds/15 minutes)
     *
     * @param   int  $timeout
     *
     * @return  int
     * @since   1.0
     */
    public function setTimeout($timeout = 900)
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

        return;
    }

    /**
     * Get the Timeout
     *
     * @return  int
     * @since   1.0
     */
    public function getTimeout()
    {
        return (int)$this->timeout;
    }

    /**
     * Set the Passive Indicator
     *
     * @return  bool
     * @since   1.0
     */
    public function setPassiveMode()
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

        return;
    }

    /**
     * Get Passive Mode Setting
     *
     * @return  int
     * @since   1.0
     */
    public function getPassiveMode()
    {
        return $this->passive_mode;
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
            (ucfirst(strtolower($this->getFilesystemType())) . ' Filesystem:  not a valid file. Path: '
                . $this->path);
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
            (ucfirst(strtolower($this->getFilesystemType())) . ' Filesystem:  not a valid file. Path: '
                . $this->path);
        }

        $this->no_extension = pathinfo($this->path, PATHINFO_FILENAME);

        return;
    }

    /**
     * Get the file size of a given file.
     *
     * @param   bool $recursive
     *
     * @return  void
     * @since   1.0
     */
    public function getSize($recursive = true)
    {
        $this->size = 0;

        if (count($this->files) > 0 && is_array($this->files)) {

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
     * @throws  FilesystemException
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
            throw new FilesystemException
            (ucfirst(strtolower($this->getFilesystemType())) . ' Filesystem:  getMimeType either '
                . ' finfo_open or mime_content_type are required in PHP');
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
            (ucfirst(strtolower($this->getFilesystemType())) . ' Filesystem getCreateDate failed for ' . $this->path);
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
            (ucfirst(strtolower($this->getFilesystemType())) . ' Filesystem getAccessDate failed for ' . $this->path);
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

            (ucfirst(strtolower($this->getFilesystemType())) . ' Filesystem:  getModifiedDate method failed for '
                . $this->path);
        }

        return;
    }

    /**
     * Tests if the current user has read access
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

    /**
     * Adapter Interface Step 4:
     *
     * Execute the action requested
     *
     * @param   string  $action
     *
     * @return  void
     * @since   1.0
     * @throws  FilesystemException
     * @throws  BadMethodCallException
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
                    throw new BadMethodCallException
                    (ucfirst(strtolower($this->getFilesystemType()))
                        . ' Filesystem:  Must provide relative_to_this_path for relative_path request. Path: '
                        . $this->path);
                }

                $this->getRelativePath($relative_to_this_path);

                break;

            case 'changepermission':

                $permission = null;

                if (isset($this->options['permission'])) {
                    $permission = $this->options['permission'];
                }
                if ($permission === null) {
                    throw new FilesystemException
                    ($this->getFilesystemType(
                    ) . ' Filesystem doAction Method ' . $action . ' no permission value provided.');
                }
                $this->changePermission($permission);

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
     * @throws  NotFoundException when file does not exist
     */
    public function read()
    {
        if ($this->exists === false) {
            throw new NotFoundException
            (ucfirst(strtolower($this->getFilesystemType())) . ' Filesystem Read: File does not exist: ' . $this->path);
        }

        if ($this->is_file === false) {
            throw new NotFoundException
            (ucfirst(strtolower($this->getFilesystemType())) . ' Filesystem Read: Is not a file: ' . $this->path);
        }

        if ($this->is_readable === false) {
            throw new NotFoundException
            (ucfirst(
                strtolower($this->getFilesystemType())
            ) . ' Filesystem Read: Not permitted to read: ' . $this->path);
        }

        try {
            $this->data = file_get_contents($this->path);

        } catch (\Exception $e) {

            throw new NotFoundException
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
     * @param   bool    $recursive
     * @param   bool    $exclude_files
     * @param   bool    $exclude_folders
     * @param   array   $extension_list
     * @param   string  $name_mask
     *
     * @return  void
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
            $this->read();
            return;
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
            $this->append($data);
            return;
        }

        if ($truncate === true) {
            $this->truncate();
            return;
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

                return;
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

            throw new NotFoundException
            (ucfirst(strtolower($this->getFilesystemType()))
                . ' Filesystem:  error writing file ' . $this->path . '/' . $file);
        }

        return;
    }

    /**
     * Append data to file identified in path using the data value
     *
     * @param   string  $data
     *
     * @return  void
     * @since   1.0
     * @throws  FilesystemException
     * @throws  NotFoundException
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

            throw new NotFoundException
            (ucfirst(strtolower($this->getFilesystemType())) . ' Filesystem:  error appending to file '
                . $this->path);
        }

        return;
    }

    /**
     * Truncate file identified in path using the data value
     *
     * @return  void
     * @since   1.0
     * @throws  FilesystemException
     * @throws  NotFoundException
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

            throw new NotFoundException
            (ucfirst(strtolower($this->getFilesystemType())) . ' Filesystem:  error truncating file ' . $this->path);
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
            (ucfirst(
                strtolower($this->getFilesystemType())
            ) . ' Filesystem Delete: Request to delete root is not allowed'
                . $this->path);
        }

        if (file_exists($this->path)) {
        } else {

            throw new NotFoundException
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
            $target_filesystem_type = $this->getFilesystemType();
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

        return;
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
     * Changes the owner for the file or folder identified in path
     *
     * @param   string  $user_name
     *
     * @return  void
     * @throws  FilesystemException
     * @since   1.0
     */
    public function changeOwner($user_name)
    {
        try {
            chown($this->path, $user_name);

        } catch (Exception $e) {

            throw new FilesystemException
            (ucfirst(strtolower($this->getFilesystemType()))
                . ' Filesystem: changeOwner method failed for Path: ' . $this->path
                . ' Owner: ' . $user_name);
        }

        return;
        /**
        // Check the result
        $stat = stat($path);
        print_r(posix_getpwuid($stat['uid']));
         */
    }

    /**
     * Changes the group for the file or folder identified in path
     *
     * @param   int  $group_id
     *
     * @return  void
     * @throws  FilesystemException
     * @since   1.0
     */
    public function changeGroup($group_id)
    {
        try {
            chgrp($this->path, $group_id);

        } catch (Exception $e) {

            throw new FilesystemException
            (ucfirst(strtolower($this->getFilesystemType()))
                . ' Filesystem: changeGroup method failed for Path: ' . $this->path
                . ' Group: ' . $group_id);
        }

        return;
    }

    /**
     * Change the file mode for user for read, write, execute access
     *
     * @param   int   $permission
     *
     * @return  void
     * @throws  FilesystemException
     * @since   1.0
     */
    public function changePermission($permission)
    {
        try {
            chmod($this->path, octdec(str_pad($permission, 4, '0', STR_PAD_LEFT)));

        } catch (Exception $e) {

            throw new FilesystemException
            (ucfirst(strtolower($this->getFilesystemType()))
                . ' Filesystem: changePermission method failed for Path: ' . $this->path
                . ' Permissions: ' . octdec(str_pad($permission, 4, '0', STR_PAD_LEFT)));
        }
    }

    /**
     * Update the touch time and/or the access time for the directory or file identified in the path
     *
     * @param   int     $modification_time
     * @param   int     $access_time
     *
     * @return  void
     * @throws  FilesystemException
     * @since   1.0
     */
    public function touch($modification_time = null, $access_time = null)
    {
        if ($modification_time == '' || $modification_time === null || $modification_time == 0) {
            $modification_time = $this->getDateTime($modification_time);
        }

        try {

            touch($this->path, $modification_time, $access_time);

        } catch (Exception $e) {

            throw new FilesystemException
            (ucfirst(strtolower($this->getFilesystemType()))
                . ' Filesystem: touch method failed for Path: ' . $this->path
                . ' Modification Time: ' . (string) $modification_time
                . ' Access Time: ' . (string) $access_time);
        }

        return;
    }

    /**
     * Destruct Method
     *
     * @return  void
     * @since   1.0
     */
    public function __destruct()
    {
        if (is_resource($this->connection)) {
            $this->close();
        }

        return;
    }

    /**
     * Close the Ftp Connection
     *
     * @return  void
     * @since   1.0
     * @throws  \Exception
     */
    public function close()
    {
        if ($this->is_connected === true) {
            try {
                ftp_close($this->connection);

            } catch (\Exception $e) {

                throw new \Exception
                ('Filesystem Adapter Ftp: Closing Ftp Connection Failed');
            }
        }

        return;
    }

    /**
     * Get Date Time
     *
     * @param   string       $time
     * @param   DateTimeZone $timezone
     *
     * @return  DateTime
     */
    public function getDateTime($time, DateTimeZone $timezone = null)
    {
        if ($time instanceof DateTime) {
            return $time;
        }

        if (is_int($time) || is_float($time)) {
            //todo test and remove @
            $time = new DateTime('@' . intval($time), $timezone);

        } else {

            $time = new DateTime(null, $timezone);
        }

        return $time;
    }

    /**
     * Normalizes the given path
     *
     * @param   $path
     *
     * @return  string
     * @since   1.0
     */
    public function normalise($path = '')
    {
        if ($path == '') {
            $this->path = $path;
        }

        /**  Filter: empty value
         *
         * @link http://tinyurl.com/arrayFilterStrlen
         */
        $nodes = array_filter(explode('/', $path), 'strlen');

        /** Determine if it is absolute path */
        $absolute_path = false;
        if (substr($path, 0, 1) == '/') {
            $absolute_path = true;
            $path          = substr($path, 1, strlen($path));
        }

        /** Unescape slashes */
        $path = str_replace('\\', '/', $path);

        /** Get rid of the '.' and '..' layers */
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
     * @return  void
     */
    public function discovery()
    {
        $this->directories = array();
        $this->files       = array();

        if ($this->exists === true) {
        } else {
            return;
        }

        if ($this->is_file === true) {
            $this->files[] = $this->path;
        }

        if (is_dir($this->path)) {
        } else {
            return;
        }

        if (count(glob($this->path . '/*')) == 0) {
            return;
        }

        $this->directories[] = $this->path;

        $objects = new RecursiveIteratorIterator (
            new RecursiveDirectoryIterator($this->path),
            RecursiveIteratorIterator::SELF_FIRST);

        if (count($objects) === 0 && is_array($objects)) {
            return;
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

        return;
    }

    /**
     * Common method for creating new path for copy or move
     *
     * @param   string  $path (file or folder)
     * @param   string  $target_directory
     * @param   string  $base_folder
     *
     * @since   1.0
     * @return  string
     */
    public function build_new_path($path, $target_directory, $base_folder)
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
     * @param   bool  $variable
     * @param   bool  $default
     *
     * @return  bool
     * @since   1.0
     */
    public function setTorF($variable, $default = false)
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
     * Extensions for Text files
     *
     * @return string
     * @since  1.0
     */
    public function textFileExtensions()
    {
        return '(app|avi|doc|docx|exe|ico|mid|midi|mov|mp3|
                 mpg|mpeg|pdf|psd|qt|ra|ram|rm|rtf|txt|wav|word|xls)';
    }
}
