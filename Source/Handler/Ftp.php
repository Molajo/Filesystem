<?php
/**
 * Ftp Filesystem Type
 *
 * @package    Molajo
 * @copyright  2013 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Filesystem\Handler;

use Exception;

use Exception\Filesystem\FtpHandlerException;

/**
 * Ftp Filesystem Type
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2013 Amy Stephen. All rights reserved.
 * @since      1.0
 */
class Ftp extends AbstractHandler
{
    /**
     * Temp - stream file for transferring FTP content
     *
     * @var    resource
     * @since  1.0
     */
    private $stream;

    /**
     * Temp Files - used when parsing ftprawlist for metadata
     *
     * @var    array
     * @since  1.0
     */
    private $temp_files = array();

    /**
     * Temp - used to identify Windows FTP Servers
     *
     * @var    $is_windows ;
     * @since  1.0
     */
    protected $is_windows;

    /**
     * Error messages and codes
     *
     * @var    $is_windows ;
     * @since  1.0
     */
    protected static $error_messages = array(
        '01' => 'FTP_INTERNAL_ERROR',
        '02' => 'FTP_SERVER_ERROR',
        '04' => 'FTP_INVALID_PARAM',
        '05' => 'FTP_OPEN_IOSTREAM_FAILED',
        '06' => 'FTP_ALREADY_CONNECTED',
        '07' => 'FTP_USAGE',
        '08' => 'FTP_CONNECT_FAILED',
        '09' => 'FTP_TIMEOUT',
        '10' => 'FTP_SESSION_ERROR',
        '11' => 'FTP_LOGIN_FAILED',
        '12' => 'FTP_INPUT_ERR',
        '13' => 'FTP_INPUT_EOF',
        '14' => 'FTP_NOTFOUND',
        '15' => 'FTP_INVALID_ENVIRONMENT',
        '16' => 'FTP_NOT_ENABLED',
        '17' => 'FTP_AUTHENTICATION',
        '18' => 'FTP_FILE_ACCESS',
        '19' => 'FTP_FILE_READ',
        '20' => 'FTP_FILE_WRITE',
        '21' => 'FTP_CONVERSION',
        '22' => 'FTP_PROXY_ERR',
        '23' => 'FTP_SQL_ERR',
        '24' => 'FTP_CLIENT_ERR',
        '25' => 'FTP_EOD_BEFORE_EOF',
        '26' => 'FTP_NEEDS_CONNECTION'
    );

    /**
     * Filesystem Handler
     *
     * @var    string
     * @since  1.0
     */
    protected $handler = 'Local';

    /**
     * Constructor
     *
     * @param   array $options
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
     * @returns  $this
     * @since    1.0
     * @throws   FtpHandlerException
     */
    public function connect($options = array())
    {
        if ($this->is_connected === true) {
            return;
        }

        try {
            if ($this->getConnectionType() == 'ftps') {

                if (function_exists('ftp_ssl_connect')) {

                    throw new FtpHandlerException
                    ('ftp_ssl_connect must be enabled in PHP to use SSL over Ftp');
                }

                $id = \ftp_ssl_connect($this->host, $this->port, $this->timeout);
            } else {
                $id = \ftp_connect($this->host, $this->port, $this->timeout);
            }

            $this->setConnection($id);
        } catch (Exception $e) {
            throw new FtpHandlerException
            ('Filesystem Handler Ftp: Unable to connect to the Ftp Server '
            . ' Host: ' . $this->host . ' Port: ' . $this->port);
        }

        if ($this->is_connected === false) {
            throw new FtpHandlerException
            ('Filesystem Handler Ftp: Not connected '
            . ' Host: ' . $this->host . ' Port: ' . $this->port);
        }

        try {
            \ftp_pasv($this->connection, $this->getPassiveMode());
        } catch (Exception $e) {

            throw new FtpHandlerException
            ('Filesystem Handler Ftp: Unable to set passive mode for Ftp Server '
            . ' Host: ' . $this->host . ' Port: ' . $this->port);
        }

        try {
            $this->login();
        } catch (Exception $e) {

            throw new FtpHandlerException
            ('Filesystem Handler Ftp: Login failed for ' . ' User: ' . $this->username
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
        } catch (Exception $e) {

            throw new FtpHandlerException
            ('Filesystem Handler Ftp: Login failed for ' . ' User: ' . $this->username
            . ' Host: ' . $this->host . ' Port: ' . $this->port);
        }

        try {
            if ($this->initial_directory === null) {
                $results = true;
            } else {
                $results = \ftp_chdir($this->connection, $this->initial_directory);
            }
        } catch (Exception $e) {

            throw new FtpHandlerException
            ('Filesystem Handler Ftp: Changing Ftp Directory failed. Directory: '
            . $this->initial_directory);
        }

        if ($results === false) {

            throw new FtpHandlerException
            ('Filesystem Handler Ftp: Unable to change directory: '
            . $this->root . ' for Ftp Server '
            . ' Host: ' . $this->host . ' Port: ' . $this->port);
        }

        return;
    }

    /**
     * Method to login to a server once connected
     *
     * @return bool
     * @since   1.0
     * @throws  FtpHandlerException
     */
    protected function login()
    {
        $logged_in = ftp_login($this->getConnection(), $this->getUsername(), $this->getPassword());

        if ($logged_in === true) {
        } else {

            throw new FtpHandlerException
            ('Filesystem Handler Ftp: Unable to login with Password: ' . $this->getPassword()
            . ' Password: ' . $this->getPassword());
        }

        return true;
    }

    ////////// Begin Metadata /////////////

    /**
     * Set the Path
     *
     * @param   string $path
     *
     * @return  $this
     * @since   1.0
     */
    protected function setPath($path)
    {
        $this->path = $this->normalise($path);

        return $this;
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
     * Handler Interface Step 3:
     *
     * Retrieves and sets metadata for the file specified in path
     *
     * @return void
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
     * @return mixed
     * @since   1.0
     * @throws  FtpHandlerException
     */
    protected function read()
    {
        if ($this->exists === false) {
            throw new FtpHandlerException
            ('Ftp Filesystem Read: File does not exist: ' . $this->path);
        }

        if ($this->is_file === false) {
            throw new FtpHandlerException
            ('Ftp Filesystem Read: Is not a file: ' . $this->path);
        }

        /** Already available in $this->stream file */
        if ($this->stream === null) {
        } else {
            return;
        }

        try {

            $this->stream = fopen('php://temp', 'r+');

            if (ftp_fget($this->getConnection(), $this->stream, $this->path, $this->ftp_mode)) {
            } else {
                throw new FtpHandlerException ('FTP Filesystem: Read failed for: ' . $this->path);
            }
        } catch (Exception $e) {

            throw new FtpHandlerException
            ('Ftp Filesystem Read: Error reading file: ' . $this->path);
        }

        try {
            rewind($this->stream);
            $this->data = stream_get_contents($this->stream);
            fclose($this->stream);
        } catch (Exception $e) {
            throw new FtpHandlerException
            ('Ftp Filesystem Read: Error reading file: ' . $this->path . ' ' . $e->getMessage());
        }

        if ($this->data === false) {
            ('Ftp Filesystem Read: Empty File: ' . $this->path);
        }

        return;
    }

    /**
     * For a file request, creates, appends to, replaces or truncates the file identified in path
     * For a folder request, create is the only valid option
     *
     * @param string $file
     * @param string $data
     * @param bool   $replace
     * @param bool   $append
     * @param bool   $truncate
     *
     * @return void
     * @since   1.0
     * @throws  FtpHandlerException
     */
    protected function write($file = '', $data = '', $replace = false, $append = false, $truncate = false)
    {
        if ($append === true) {
            $this->append_or_truncate($data, 'append');

            return;
        }

        if ($truncate === true) {
            $this->append_or_truncate(null, 'truncate');

            return;
        }

        if ($this->exists === false) {
        } elseif ($this->is_file === true || $this->is_directory === true) {
        } else {
            throw new FtpHandlerException
            (ucfirst(
                strtolower($this->getAdapterHandler())
            ) . ' Filesystem Write: must be directory or file: ' . $this->path . '/' . $file);
        }

        if (trim($data) == '' || strlen($data) == 0) {
            if ($this->is_file === true) {

                throw new FtpHandlerException
                (ucfirst(strtolower($this->getAdapterHandler()))
                . ' Filesystem:  attempting to write no data to file: ' . $this->path . '/' . $file);
            }
        }

        if (trim($data) == '' || strlen($data) == 0) {
            if ($file == '') {
                throw new FtpHandlerException
                (ucfirst(strtolower($this->getAdapterHandler()))
                . ' Filesystem:  attempting to write no data to file: ' . $this->path . '/' . $file);
            } else {
                $this->createDirectory($this->path . '/' . $file);

                return;
            }
        }

        if ($this->is_file === true) {
        } else {
            $this->createDirectory($this->path);
        }

        try {

            $stream = fopen('php://temp', 'r+');
            fwrite($stream, $data);
            rewind($stream);

            if (ftp_fput($this->getConnection(), $this->path, $stream, $this->ftp_mode)) {
            } else {
                throw new FtpHandlerException ('FTP Filesystem Write failed for ' . $this->path);
            }
        } catch (Exception $e) {

            fclose($this->stream);

            throw new FtpHandlerException
            (ucfirst(strtolower($this->getAdapterHandler()))
            . ' Filesystem:  error writing file ' . $this->path . '/' . $file);
        }

        fclose($this->stream);

        return;
    }

    /**
     * Append data to file identified in path using the data value
     *
     * @param   null   $data
     * @param   string $type
     *
     * @return  void
     * @since   1.0
     * @throws  FtpHandlerException
     */
    private function append_or_truncate($data = null, $type = 'append')
    {
        if ($this->exists === true) {
        } elseif ($this->is_file === false) {
        } else {
            throw new FtpHandlerException
            (ucfirst(strtolower($this->getAdapterHandler()))
            . ' Filesystem:  attempting to append to a folder, not a file ' . $this->path);
        }

        $this->stream = fopen('php://temp', 'r+');

        if (ftp_fget($this->getConnection(), $this->stream, $this->path, $this->ftp_mode)) {
        } else {
            throw new FtpHandlerException ('FTP Filesystem Append: Read failed for: ' . $this->path);
        }

        try {
            rewind($this->stream);
            $appended = stream_get_contents($this->stream);
            $appended .= $data;

            if ($type === 'append') {
            } else {
                $appended = null;
            }

            fwrite($this->stream, $appended);
            rewind($this->stream);
        } catch (Exception $e) {

            throw new FtpHandlerException
            (ucfirst(strtolower($this->getAdapterHandler()))
            . ' Filesystem:  error writing stream for appending to ' . $this->path);
        }

        try {

            if (ftp_fput($this->getConnection(), $this->path, $this->stream, $this->ftp_mode)) {
            } else {
                throw new FtpHandlerException ('FTP Filesystem Write failed for ' . $this->path);
            }
        } catch (Exception $e) {

            throw new FtpHandlerException
            (ucfirst(strtolower($this->getAdapterHandler()))
            . ' Filesystem:  error writing file ' . $this->path);
        }

        fclose($this->stream);

        return;
    }

    /**
     * Create Directory
     *
     * @param bool $path
     *
     * @return void
     * @since   1.0
     * @throws  FtpHandlerException
     */
    protected function createDirectory($path)
    {
        if (file_exists($path)) {
            return;
        }

        /** recursively create parents for FTP */
        $parent = dirname($path);
        if ($parent === false || $parent = '' || $parent === null) {
        } else {
            $this->createDirectory($parent);
        }

        try {
            $success = ftp_mkdir($this->getConnection(), $path);
            if ($success === false) {
                throw new FtpHandlerException ('Unable to create FTP folder: ' . $path);
            }
        } catch (Exception $e) {

            throw new FtpHandlerException
            ('FTP Filesystem Create Directory: error creating directory: ' . $e->getMessage());
        }

        return;
    }

    /**
     * Get Ftp Metadata for parsing and using to see if the path exists
     *
     * @return void
     */
    protected function getFtpMetadata()
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

            if ($metadata->name == '.' || $metadata->name == '..') {
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
    protected function exists()
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
     * Sets true or false indicator as to whether or not the path is a directory
     *
     * @ - added to prevent PHP from throwing a warning if it is a file, not a directory
     *
     * @return void
     * @since   1.0
     */
    protected function isDirectory()
    {
        $this->is_directory = $this->checkIsDirectory();

        if ($this->is_directory === true) {
            $this->is_file = false;
        }

        return;
    }

    /**
     * Sets true or false indicator as to whether or not the path is a directory
     *
     * @ - added to prevent PHP from throwing a warning if it is a file, not a directory
     *
     * @return bool
     * @since   1.0
     */
    protected function isParentDirectory()
    {
        return $this->checkIsDirectory();
    }

    /**
     * Shared by isDirectory and isParentDirectory
     *
     * @ - added to prevent PHP from throwing a warning if it is a file, not a directory
     *
     * @return bool
     * @since   1.0
     */
    protected function checkIsDirectory()
    {
        $indicator = false;

        $current = ftp_pwd($this->getConnection());

        try {
            if (@ftp_chdir($this->getConnection(), $this->path)) {
                $indicator = true;
            }
        } catch (Exception $e) {
        }

        ftp_chdir($this->getConnection(), $current);

        return $indicator;
    }

    /**
     * Returns true or false indicator as to whether or not the path is a file
     *
     * @return void
     * @since   1.0
     * @throws  FtpHandlerException
     */
    protected function isFile()
    {
        $this->is_file = false;

        try {
            $this->stream = fopen('php://temp', 'r+');

            if (ftp_fget($this->getConnection(), $this->stream, $this->path, $this->ftp_mode, 0)) {
                $this->is_file = true;
            } else {
                throw new FtpHandlerException
                ('FTP Filesystem: IsFile ftp_get failed ' . $this->path);
            }
        } catch (Exception $e) {
            throw new FtpHandlerException
            ('FTP Filesystem isFile: Failed ' . $e->getMessage());
        }

        try {
            rewind($this->stream);
            $this->data = stream_get_contents($this->stream);
            fclose($this->stream);
        } catch (Exception $e) {
            throw new FtpHandlerException
            ('Ftp Filesystem Read: Error reading file: ' . $this->path . ' ' . $e->getMessage());
        }

        return;
    }

    /**
     * Not implemented
     *
     * @return void
     * @since   1.0
     */
    protected function isLink()
    {
        $this->is_link = false;
    }

    /**
     * Get Parent
     *
     * @return void
     * @since   1.0
     * @throws  FtpHandlerException
     */
    protected function getParent()
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
        } catch (Exception $e) {
        }

        try {
            ftp_cdup($this->getConnection());

            $this->parent = ftp_pwd($this->getConnection());
        } catch (Exception $e) {
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
    protected function getSize()
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
     * @return void
     * @throws  FtpHandlerException
     * @since   1.0
     */
    protected function getMimeType()
    {
        $this->mime_type = null;

        if ($this->exists === false) {
            return;
        }

        if ($this->is_file === true) {
        } else {
            return;
        }

        $temp_mimetype = $this->getMimeArray();

        if ($temp_mimetype === null) {
            $this->mime_type = 'application/octet-stream';
        } else {
            $this->mime_type = $temp_mimetype;
        }

        return;
    }

    /**
     * Returns the owner of the file or directory defined in the path
     *
     * @return void
     * @since   1.0
     */
    protected function getOwner()
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
    protected function getGroup()
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
     * @throws  FtpHandlerException
     */
    protected function getCreateDate()
    {
        $this->create_date = null;

        return;
    }

    /**
     * Not implemented
     *
     * @return void
     * @since   1.0
     * @throws  FtpHandlerException
     */
    protected function getAccessDate()
    {
        $this->access_date = null;

        return;
    }

    /**
     * Retrieves Last Update Date for directory or file identified in the path
     *
     * @return void
     * @since   1.0
     * @throws  FtpHandlerException
     */
    protected function getModifiedDate()
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
    protected function isReadable()
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
    protected function isWriteable()
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
    protected function isExecutable()
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
    protected function hashFileMd5()
    {
        $this->hash_file_md5 = null;

        if ($this->exists === true) {
        } elseif ($this->is_file === true) {
        } else {
            return;
        }

        $this->hash_file_md5 = md5($this->data);

        return;
    }

    /**
     * Hash file sha1
     * http://www.php.net/manual/en/function.sha1-file.php
     *
     * @return void
     * @since   1.0
     */
    protected function hashFileSha1()
    {
        $this->hash_file_sha1 = null;

        if ($this->exists === true) {
        } elseif ($this->is_file === true) {
        } else {
            return;
        }

        $this->hash_file_sha1 = sha1($this->data);

        return;
    }

    /**
     * Hash file sha1 20
     * http://www.php.net/manual/en/function.sha1-file.php
     *
     * @return void
     * @since   1.0
     */
    protected function hashFileSha1_20()
    {
        $this->hash_file_sha1_20 = null;

        if ($this->exists === true) {
        } elseif ($this->is_file === true) {
        } else {
            return;
        }

        $this->hash_file_sha1_20 = sha1($this->data, true);

        return;
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
}
