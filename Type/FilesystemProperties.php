<?php
/**
 * Filesystem Properties
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

/**
 * Filesystem Properties
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
abstract class FilesystemProperties
{
    /**
     * Start-up
     */

    /**
     * Filesystem Type
     *
     * @var    string
     * @since  1.0
     */
    public $filesystem_type;

    /**
     * Options
     *
     * @var    string
     * @since  1.0
     */
    public $options;

    /**
     * Discovery directories and folders for Working Properties
     */

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
     * Output
     */

    /**
     * Action Results
     *
     * @var     mixed
     * @since   1.0
     */
    public $data;

    /**
     * File
     */

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
     * Metadata
     */

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
    public $no_extension;

    /**
     * Extension
     *
     * @var    string
     * @since  1.0
     */
    public $extension;

    /**
     * Mime Type
     *
     * @var    string
     * @since  1.0
     */
    public $mime_type;

    /**
     * Size
     *
     * @var    int
     * @since  1.0
     */
    public $size;

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
     * @var    object  Datetime
     * @since  1.0
     */
    public $create_date;

    /**
     * Access Date
     *
     * @var    object  Datetime
     * @since  1.0
     */
    public $access_date;

    /**
     * Modified Date
     *
     * @var    object  Datetime
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
     * System
     */

    /**
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
     * Connection Type
     *
     * @var    string
     * @since  1.0
     */
    public $connection_type;

    /**
     * Port
     *
     * @var    string
     * @since  1.0
     */
    public $port = 21;

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
     * Timezone
     *
     * @var    bool
     * @since  1.0
     */
    public $timezone;

    /**
     *  SystemInterface
     */

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

        if (isset($this->options['timezone'])) {
            $this->timezone = $this->options['timezone'];
        } else {
            $this->timezone = 'GMT';
        }

        $this->setUsername();
        $this->setPassword();
        $this->setPort();
        $this->setHost();
        $this->setConnectionType();
        $this->setTimeout();
        $this->setPassiveMode();

        $this->setRoot();
        $this->setPersistence();
        $this->setDirectoryDefaultPermissions();
        $this->setFileDefaultPermissions();
        $this->setReadOnly();
        $this->setInitialDirectory();

        return;
    }

    /**
     * Set Root of Filesystem
     *
     * @return  void
     * @since   1.0
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

        throw new \RuntimeException
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
            $this->read_only = $read_only;
        } else {
            $this->read_only = $read_only;
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
     * Set Connection
     *
     * @param   integer  $connection
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
     *  Connection
     */

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

        if ($password === null) {
        } else {
            $this->password = $password;
        }

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

        if ($host === null) {
            $this->host = 'host';
        } else {
            $this->host = $host;
        }

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

        if ($port === null) {
            $this->port = 21;
        } else {
            $this->port = $port;
        }

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

        if ($connection_type === 'ftps') {
            $this->connection_type = 'ftps';
        } else {
            $this->connection_type = 'ftp';
        }

        return;
    }

    /**
     * Get the Connection Type
     *
     * @return  mixed
     * @since   1.0
     */
    public function getConnectType()
    {
        return $this->connection_type;
    }

    /**
     * Set the Timeout
     *
     * @param   int  $timeout
     *
     * @return  int
     * @since   1.0
     */
    public function setTimeout($timeout = 15)
    {
        $timeout = null;

        if (isset($this->options['timeout'])) {
            $timeout = $this->options['timeout'];
        }

        if ($timeout === null) {
            $this->timeout = 21;
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
        return  $this->passive_mode;
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
     * Close the FTP Connection
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
                ('Filesystem Adapter FTP: Closing FTP Connection Failed');
            }
        }

        return;
    }

    /**
     *  Utilities
     */

    /**
     * Get Date Time
     *
     * @param   $time
     *
     * @return  object  Datetime
     * @since   1.0
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

        if (count($objects) === 0) {
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
