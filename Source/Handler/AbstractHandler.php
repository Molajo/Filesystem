<?php
/**
 * Filesystem Abstract Type
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Filesystem\Handler;

use DateTime;
use DateTimeZone;
use Exception\Filesystem\AbstractHandlerException;

/**
 * Filesystem Abstract Handler
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @since      1.0
 */
abstract class AbstractHandler
{
    /**
     * Filesystem Handler
     *
     * @var    string
     * @since  1.0
     */
    protected $handler;

    /**
     * Path
     *
     * @var    string
     * @since  1.0
     */
    protected $path;

    /**
     * Options
     *
     * @var    array
     * @since  1.0
     */
    protected $options;

    /**
     * Timezone
     *
     * @var    DateTimeZone
     * @since  1.0
     */
    protected $timezone;

    /**
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
     * FTP Mode - FTP_ASCII or FTP_BINARY
     *
     * @var    bool
     * @since  1.0
     */
    protected $ftp_mode = FTP_ASCII;

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
    protected $file_name_without_extension;

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
     * Constructor
     *
     * @param   array  $options
     * @param   string $handler
     *
     * @since   1.0
     */
    public function __construct(array $options = array())
    {
        $this->connect($options);
    }

    /**
     * Handler Interface Step 1:
     *
     * Method to connect to a Local server
     *
     * @param    array $options
     *
     * @return   $this
     * @since    1.0
     */
    public function connect($options = array())
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
        $this->setDefaultDirectoryPermissions();
        $this->setDefaultFilePermissions();
        $this->setReadOnly();

        return $this;
    }

    /**
     * Set Filesystem Type
     *
     * @param   string $handler
     *
     * @return  $this
     * @since   1.0
     */
    protected function setAdapterHandler($handler)
    {
        $this->handler = $handler;

        return $this;
    }

    /**
     * Set Filesystem Type
     *
     * @return  string
     * @since   1.0
     */
    protected function getAdapterHandler()
    {
        return $this->handler;
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
     * @param   string $path
     *
     * @return  string
     * @since   1.0
     * @throws  AbstractHandlerException
     */
    protected function normalise($path = '')
    {
        if ($path == '') {
            $this->path = $path;
        }

        if ($path == '') {
            throw new AbstractHandlerException
            ('Filesystem: No Path sent into AbstractHandler::Normalise');
        }

        /** Unescape slashes */
        $path = str_replace('\\', '/', $path);

        /** Determine if it is absolute path */
        if (substr($path, 0, 1) == '/') {
        } else {
            throw new AbstractHandlerException
            ('Filesystem: AbstractHandler requires Absolute Path: ' . $this->path . ' is invalid.');
        }

        $path = substr($path, 1, strlen($path));

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

        $path = '/' . $path;

        return $path;
    }

    /**
     * Common method for creating new path for copy or move
     *
     * @param   string $path (file or folder)
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
     * Utility method - return mimetype text for extension type
     *
     * @return  string
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

    ////////// Begin Connection Methods /////////////

    /**
     * Set the username
     *
     * @return  $this
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

        return $this;
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
     * @return  string
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

        $this->port = (int)$port;

        return $this;
    }

    /**
     * Get the Port
     *
     * @return  int
     * @since   1.0
     */
    protected function getPort()
    {
        return (int)$this->port;
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
            $this->connection_type = strtolower($this->getAdapterHandler());
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
     * @throws  AbstractHandlerException
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

        throw new AbstractHandlerException
        ('Filesystem Abstract Handler: Root is not a valid directory. ' . $root);
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
    protected function setDefaultDirectoryPermissions()
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
    protected function getDefaultDirectoryPermissions()
    {
        return $this->default_directory_permissions;
    }

    /**
     * Set Default Filesystem Permissions for Files
     *
     * @return  $this
     * @since   1.0
     */
    protected function setDefaultFilePermissions()
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
    protected function getDefaultFilePermissions()
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
}
