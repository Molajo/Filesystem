<?php
/**
 * Filesystem Abstract Type
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Filesystem\Adapter;

defined('MOLAJO') or die;

use DateTime;
use DateTimeZone;

/**
 * Filesystem Abstract Adapter
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
abstract class AbstractAdapter
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
     * @var    array
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
     * Initial Directory for Filesystem
     *
     * @var    string
     * @since  1.0
     */
    protected $initial_directory;

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
     * Constructor
     *
     * @param   array  $options
     * @param   string $filesystem_type
     *
     * @since   1.0
     */
    public function __construct($options = array(), $filesystem_type = '')
    {
        $this->setFilesystemType($filesystem_type);
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
