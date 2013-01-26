<?php
/**
 * System Adapter for Filesystem
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem;

defined ('MOLAJO') or die;


use Molajo\Filesystem\SystemInterface;

use \Exception;
use Molajo\Filesystem\Exception\FileException as FileException;

/**
 * Abstract System Class for Filesystem
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class System implements SystemInterface
{
    /**
     * Options
     *
     * @var    array
     * @since  1.0
     */
    protected $options;

    /**
     * Root Directory for Filesystem
     *
     * @var    string
     * @since  1.0
     */
    protected $root;

    /**
     * Persistence
     *
     * @var    bool
     * @since  1.0
     */
    protected $persistence;

    /**
     * Path
     *
     * @var    string
     * @since  1.0
     */
    protected $path;

    /**
     * Type: Directory, File, Link
     *
     * @var    string
     * @since  1.0
     */
    protected $type;

    /**
     * Absolute Path for Filesystem
     *
     * @var    string
     * @since  1.0
     */
    protected $absolute_path;

    /**
     * Directory
     *
     * @var    string
     * @since  1.0
     */
    protected $directory;

    /**
     * Directory Permissions
     *
     * @var    string
     * @since  1.0
     */
    protected $directory_permissions;

    /**
     * File Permissions
     *
     * @var    string
     * @since  1.0
     */
    protected $file_permissions;

    /**
     * Read only
     *
     * @var    string
     * @since  1.0
     */
    protected $read_only;

    /**
     * Constructor
     *
     * @param          $path
     * @param   array  $options
     *
     * @since   1.0
     */
    public function __construct ($path, $options = array())
    {
        $this->options = $options;

        if (isset($this->options['root'])) {
            $this->setRoot ($this->options['root']);
        } else {
            $this->setRoot ('/');
        }

        if (isset($this->options['persistence'])) {
            $this->setPersistence ($this->options['persistence']);
        } else {
            $this->setPersistence (0);
        }

        if (isset($this->options['directory_permissions'])) {
            $this->directory_permissions = $this->options['directory_permissions'];
        } else {
            $this->directory_permissions  = '0755';
        }

        if (isset($this->options['file_permissions'])) {
            $this->file_permissions = $this->options['file_permissions'];
        } else {
            $this->file_permissions  = '0644';
        }

        if (isset($this->options['read_only'])) {
            $this->read_only = $this->options['read_only'];
        } else {
            $this->read_only  = '0444';
        }

        return;
    }

    /**
     * Method to connect to a Local server
     *
     * @return  object|resource
     * @since   1.0
     */
    public function connect ()
    {
        return;
    }

    /**
     * Get the Connection
     *
     * @return  null
     * @since   1.0
     */
    function getConnection ()
    {
        return;
    }

    /**
     * Set the Connection
     *
     * @return  null
     * @since   1.0
     */
    function setConnection ()
    {
        return;
    }

    /**
     * Close the Local Connection
     *
     * @return  null
     * @since   1.0
     */
    public function close ()
    {
        return;
    }

    /**
     * Set Root of Filesystem
     *
     * @param   string  $root
     *
     * @return  string
     * @since   1.0
     */
    public function setRoot ($root)
    {
        $this->root = $this->normalise ($root);

        return $this->root;
    }

    /**
     * get Root of Filesystem
     *
     * @return  string
     * @since   1.0
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * Set persistence indicator for Filesystem
     *
     * @param   bool  $persistence
     *
     * @return  bool
     * @since   1.0
     */
    public function setPersistence ($persistence)
    {
        $this->persistence = $persistence;

        return $this->persistence;
    }

    /**
     * Returns the owner of the file or directory defined in the path
     *
     * @param   string  $path
     *
     * @return  bool
     * @since   1.0
     */
    public function getOwner ($path = '')
    {
        if ($path == '') {
            $path = $this->path;
        }

        $path = $this->normalise ($path);

        return fileowner ($path);
    }

    /**
     * Returns the group for the file or directory defined in the path
     *
     * @param   string  $path
     *
     * @return  string
     * @since   1.0
     */
    public function getGroup ($path = '')
    {
        if ($path == '') {
            $path = $this->path;
        }

        $path = $this->normalise ($path);

        return filegroup ($path);
    }

    /**
     * Retrieves Create Date for directory or file identified in the path
     *
     * @param   string  $path
     *
     * @return  null
     * @since   1.0
     * @throws  FileException
     */
    public function getCreateDate ($path = '')
    {
        if ($path == '') {
            $path = $this->path;
        }

        $path = $this->normalise ($path);

        if (file_exists ($path = '')) {
        } else {
            throw new FileException
            ('Filesystem: getCreateDate method. File does not exist' . $path);
        }

        try {
            echo \date ("F d Y H:i:s.", filectime ($path = ''));

        } catch (Exception $e) {
            throw new FileException

            ('Filesystem: getCreateDate method failed for ' . $path);
        }

        return;
    }

    /**
     * Retrieves Last Access Date for directory or file identified in the path
     *
     * @param   string  $path
     *
     * @return  null
     * @since   1.0
     * @throws  FileException
     */
    public function getAccessDate ($path = '')
    {
        if ($path == '') {
            $path = $this->path;
        }

        $path = $this->normalise ($path);

        if (file_exists ($path = '')) {
        } else {
            throw new FileException

            ('Filesystem: getAccessDate method. File does not exist' . $path);
        }

        try {
            echo \date ("F d Y H:i:s.", fileatime ($path));

        } catch (Exception $e) {
            throw new FileException

            ('Filesystem: getAccessDate method failed for ' . $path);
        }

        return;
    }

    /**
     * Retrieves Last Update Date for directory or file identified in the path
     *
     * @param   string  $path
     *
     * @return  null
     * @since   1.0
     * @throws  FileException
     */
    public function getModifiedDate ($path = '')
    {
        if ($path == '') {
            $path = $this->path;
        }

        $path = $this->normalise ($path);

        if (file_exists ($path = '')) {
        } else {
            throw new FileException

            ('Filesystem: getModifiedDate method. File does not exist' . $path);
        }

        try {
            echo \date ("F d Y H:i:s.", filemtime ($path));

        } catch (Exception $e) {
            throw new FileException

            ('Filesystem: getModifiedDate method failed for ' . $path);
        }

        return;
    }

    /**
     * Tests if the group specified: 'owner', 'group', or 'world' has read access
     *  Returns true or false
     *
     * @param   string  $path
     *
     * @return  bool
     * @since   1.0
     */
    public function isReadable ($path = '')
    {
        if ($path == '') {
            $path = $this->path;
        }

        $path = $this->normalise ($path);

        return is_readable ($path);
    }

    /**
     * Tests if the group specified: 'owner', 'group', or 'world' has write access
     *  Returns true or false
     *
     * @param   string  $path
     *
     * @return  bool
     * @since   1.0
     */
    public function isWriteable ($path = '')
    {
        if ($path == '') {
            $path = $this->path;
        }

        $path = $this->normalise ($path);

        return is_writable ($path);
    }

    /**
     * Tests if the group specified: 'owner', 'group', or 'world' has execute access
     *  Returns true or false
     *
     * @param   string  $path
     *
     * @return  null
     * @since   1.0
     */
    public function isExecutable ($path = '')
    {
        if ($path == '') {
            $path = $this->path;
        }

        $path = $this->normalise ($path);

        return is_executable ($path);
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
     * @param   string  $path
     * @param   int     $mode
     *
     * @return  int|null
     * @throws  FileException
     * @since   1.0
     */
    public function chmod ($path = '', $mode)
    {
        if ($path == '') {
            $path = $this->path;
        }

        $path = $this->normalise ($path = '');

        if (file_exists ($path)) {
        } else {

            throw new FileException
            ('Filesystem: chmod method. File does not exist' . $path);
        }

        if (in_array ($mode, array('0600', '0644', '0755', '0750'))) {
        } else {

            throw new FileException
            ('Filesystem: chmod method. Mode not provided: ' . $mode);
        }

        try {
            chmod ($path, $mode);

        } catch (Exception $e) {

            throw new FileException
            ('Filesystem: chmod method failed for ' . $mode);
        }

        return $mode;
    }

    /**
     * Update the touch time and/or the access time for the directory or file identified in the path
     *
     * @param   string  $path
     * @param   int     $time
     * @param   int     $atime
     *
     * @return  int|null
     * @throws  FileException
     * @since   1.0
     */
    public function touch ($path = '', $time, $atime = null)
    {
        if ($path == '') {
            $path = $this->path;
        }

        $path = $this->normalise ($path = '');

        if (file_exists ($path)) {
        } else {

            throw new FileException
            ('Filesystem: setModifiedDate method. File does not exist' . $path);
        }

        if ($time == '' || $time === null || $time == 0) {
            $time = time ();
        }

        try {

            if (touch ($path, $time)) {
                echo $path . ' modification time has been changed to present time';

            } else {
                echo 'Sorry, could not change modification time of ' . $path;
            }

        } catch (Exception $e) {

            throw new FileException
            ('Filesystem: is_readable method failed for ' . $path);
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
     * @throws  \OutOfBoundsException
     */
    public function normalise ($path = '')
    {
        if ($path == '') {
            $path = $this->path;
        }

        $absolute_path = false;
        if (substr ($path, 0, 1) == '/') {
            $absolute_path = true;
            $path          = substr ($path, 1, strlen ($path = ''));
        }

        /** Unescape slashes */
        $path = str_replace ('\\', '/', $path);
        $path = rtrim ($path) . '/';

        /**  Filter: empty value
         *
         * @link http://tinyurl.com/arrayFilterStrlen
         */
        $nodes = array_filter (explode ('/', $path), 'strlen');

        $normalised = array();

        foreach ($nodes as $node) {

            /** '.' means current - ignore it      */
            if ($node == '.') {

                /** '..' is parent - remove the parent */
            } elseif ($node == '..') {

                if (count ($normalised) > 0) {
                    array_pop ($normalised);
                }

            } else {
                $normalised[] = $node;
            }

        }

        $path = implode ('/', $normalised);
        if ($absolute_path === true) {
            $path = '/' . $path;
        }

        if (0 !== strpos ($path, $this->directory)) {
            throw new \OutOfBoundsException
            (sprintf ('The path "%s" is out of the filesystem.', $path));
        }

        return $path;
    }
}
