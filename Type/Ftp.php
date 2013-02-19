<?php
/**
 * Ftp Filesystem Type
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Filesystem\Type;

defined('MOLAJO') or die;

use Exception;
use Molajo\Filesystem\Exception\FilesystemException;

/**
 * Ftp Filesystem Type
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class Ftp extends FilesystemType
{
    /**
     * Temp - holds file name
     *
     * @var    string
     * @since  1.0
     */
    private $temp;

    /**
     * Temp Files - used when parsing ftprawlist for metadata
     *
     * @var    string
     * @since  1.0
     */
    private $temp_files = array();

    /**
     * Temp - used to identify Windows FTP Servers
     *
     * @var    $is_windows;
     * @since  1.0
     */
    public $is_windows;

    /**
     * Class constructor
     *
     * @since   1.0
     */
    public function __construct($filesystem_type)
    {
        parent::__construct($filesystem_type);

        $this->setFilesystemType('Ftp');

        return $this;
    }

    /**
     * Method to connect and logon to a Ftp server
     *
     * @param   array  $options
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
            if ($this->getConnectionType() == 'ftps') {

                if (function_exists('ftp_ssl_connect')) {

                    throw new FilesystemException
                    ('ftp_ssl_connect must be enabled in PHP to use SSL over Ftp');
                }

                $id = \ftp_ssl_connect($this->host, $this->port, $this->timeout);

            } else {
                $id = \ftp_connect($this->host, $this->port, $this->timeout);
            }

            $this->setConnection($id);

        } catch (\Exception $e) {
            throw new \InvalidArgumentException
            ('Filesystem Adapter Ftp: Unable to connect to the Ftp Server '
                . ' Host: ' . $this->host . ' Port: ' . $this->port);
        }

        if ($this->is_connected === false) {
            throw new \InvalidArgumentException
            ('Filesystem Adapter Ftp: Not connected '
                . ' Host: ' . $this->host . ' Port: ' . $this->port);
        }

        try {
            \ftp_pasv($this->connection, $this->getPassiveMode());

        } catch (\Exception $e) {

            throw new \InvalidArgumentException
            ('Filesystem Adapter Ftp: Unable to set passive mode for Ftp Server '
                . ' Host: ' . $this->host . ' Port: ' . $this->port);
        }

        try {
            $this->login();

        } catch (\Exception $e) {

            throw new \InvalidArgumentException
            ('Filesystem Adapter Ftp: Login failed for ' . ' User: ' . $this->username
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
            ('Filesystem Adapter Ftp: Login failed for ' . ' User: ' . $this->username
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
            ('Filesystem Adapter Ftp: Changing Ftp Directory failed. Directory: '
                . $this->initial_directory);
        }

        if ($results === false) {

            throw new \InvalidArgumentException
            ('Filesystem Adapter Ftp: Unable to change directory: '
                . $this->root . ' for Ftp Server '
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
            ('Filesystem Adapter Ftp: Unable to login with Password: ' . $this->getPassword()
                . ' Password: ' . $this->getPassword());
        }

        return true;
    }

    /**
     * Adapter Interface Step 2:
     *
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
        $this->getFtpMetadata();

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
     * @throws  FilesystemException when file does not exist
     */
    public function read()
    {
        if ($this->exists === false) {

            throw new FilesystemException
            ('Ftp Filesystem Read: File does not exist: ' . $this->path);
        }

        if ($this->is_file === false) {

            throw new FilesystemException
            ('Ftp Filesystem Read: Is not a file: ' . $this->path);
        }

        /** Already available in $this->temp file */
        try {
            $this->data = file_get_contents($this->temp);

        } catch (\Exception $e) {

            throw new FilesystemException
            ('Ftp Filesystem Read: Error reading file: ' . $this->path);
        }

        if ($this->data === false) {
            ('Ftp Filesystem Read: Empty File: ' . $this->path);
        }

        return;
    }

    /**
     * Get Ftp Metadata for parsing and using to see if the path exists
     *
     * @return void
     */
    public function getFtpMetadata()
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
     * Returns true or false indicator as to whether or not the path is a directory
     *
     * @ - added to prevent PHP from throwing a warning if it is a file, not a directory
     *
     * @return void
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
     * @return void
     * @since   1.0
     */
    public function isFile()
    {
        $this->is_file = false;

        try {

            if (ftp_get($this->getConnection(), $this->temp, $this->path, FTP_ASCII, 0)) {
                $this->is_file = true;
            }

        } catch (\Exception $e) {
        }

        return;
    }

    /**
     * Not implemented
     *
     * @return void
     * @since   1.0
     */
    public function isLink()
    {
        $this->is_link = false;
    }

    /**
     * Get Parent
     *
     * @return void
     * @since   1.0
     * @throws FilesystemException
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
     * @return void
     * @since   1.0
     */
    public function getSize()
    {
        $this->size = 0;

        if (count($this->temp_files) > 0) {

            foreach ($this->temp_files as $file) {
                $this->size = $this->size + (int) $file->size;
            }
        }

        return;
    }

    /**
     * Returns the mime type of the file located at path directory
     *
     * @return void
     * @throws FilesystemException
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
            throw new \FilesystemException
            ('Ftp Filesystem: getMimeType either finfo_open or mime_content_type are required in PHP');
        }

        return;
    }

    /**
     * Returns the owner of the file or directory defined in the path
     *
     * @return void
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
     * @return void
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
     * Create Date is not implemented for Ftp
     *
     * @return void
     * @since   1.0
     * @throws FilesystemException
     */
    public function getCreateDate()
    {
        $this->create_date = null;

        return;
    }

    /**
     * Not implemented
     *
     * @return void
     * @since   1.0
     * @throws FilesystemException
     */
    public function getAccessDate()
    {
        $this->access_date = null;

        return;
    }

    /**
     * Retrieves Last Update Date for directory or file identified in the path
     *
     * @return void
     * @since   1.0
     * @throws FilesystemException
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
     * @return void
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
     * @return void
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
     * @return void
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
     * @return void
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
     * @return void
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
     * @return void
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
