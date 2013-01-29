<?php
/**
 * Abstract System Class for Filesystem Adapter
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem\Targetinterface;

defined('MOLAJO') or die;

use \Exception;

use Molajo\Filesystem\Exception\FileException as FileException;

/**
 * Abstract System Class for Filesystem Adapter
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class System implements SystemInterface
{
    /**
     * Adaptee Filesystem Object
     *
     * @var    array
     * @since  1.0
     */
    protected $filesystem_type;

    /**
     * Adaptee Filesystem Connection
     *
     * @var    array
     * @since  1.0
     */
    protected $connection;

    /**
     * Root Directory for Filesystem Type
     *
     * @var    string
     * @since  1.0
     */
    protected $root;

    /**
     * Persistence of Filesystem Type
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
     * @param   array  $options
     *
     * @since   1.0
     */
    public function __construct($filesystem_type)
    {
        $this->filesystem_type = $filesystem_type;

        return $this;
    }

    /**
     * Method to connect to a Local server
     *
     * @return  object|resource
     * @since   1.0
     */
    public function connect()
    {
        $this->connection = true;

        return $this->connection;
    }

    /**
     * Set Root of Filesystem
     *
     * @param   string  $root
     *
     * @return  string
     * @since   1.0
     */
    public function setRoot($root)
    {
        $this->root = $this->filesystem_type->setRoot($root);

        return $this->root;
    }

    /**
     * Get persistence indicator for Filesystem
     *
     * @param   string  $persistence
     *
     * @return  bool
     * @since   1.0
     */
    public function setPersistence($persistence)
    {
        $this->persistence = $this->filesystem_type->setPersistence($persistence);

        return $this->persistence;
    }

    /**
     * Get Filesystem Type
     *
     * @return  string
     * @since   1.0
     */
    public function getFilesystemType()
    {
        $this->filesystem_type = $this->filesystem_type->getFilesystemType();

        return $this->filesystem_type;
    }

    /**
     * get Root of Filesystem
     *
     * @return  string
     * @since   1.0
     */
    public function getRoot()
    {
        $this->root = $this->filesystem_type->getRoot();

        return $this->root;
    }

    /**
     * Get persistence indicator for Filesystem
     *
     * @return  bool
     * @since   1.0
     */
    public function getPersistence()
    {
        $this->persistence = $this->filesystem_type->getPersistence();

        return $this->persistence;
    }

    /**
     * Returns the owner of the file or directory defined in the path
     *
     * @return  bool
     * @since   1.0
     */
    public function getOwner()
    {
        $this->owner = $this->filesystem_type->getOwner();

        return $this->owner;
    }

    /**
     * Returns the group for the file or directory defined in the path
     *
     * @return  string
     * @since   1.0
     */
    public function getGroup()
    {
        $this->group = $this->filesystem_type->getOwner();

        return $this->group;
    }

    /**
     * Retrieves Create Date for directory or file identified in the path
     *
     * @return  null
     * @since   1.0
     * @throws  FileException
     */
    public function getCreateDate()
    {
        $this->create_date = $this->filesystem_type->getCreateDate();

        return $this->create_date;
    }

    /**
     * Retrieves Last Access Date for directory or file identified in the path
     *
     * @return  null
     * @since   1.0
     * @throws  FileException
     */
    public function getAccessDate()
    {
        $this->access_date = $this->filesystem_type->getAccessDate();

        return $this->access_date;
    }

    /**
     * Retrieves Last Update Date for directory or file identified in the path
     *
     * @return  null
     * @since   1.0
     * @throws  FileException
     */
    public function getModifiedDate()
    {
        $this->modified_date = $this->filesystem_type->getModifiedDate();

        return $this->modified_date;
    }

    /**
     * Tests if the group specified: 'owner', 'group', or 'world' has read access
     *  Returns true or false
     *
     * @return  bool
     * @since   1.0
     */
    public function isReadable()
    {
        $this->is_readable = $this->filesystem_type->isReadable();

        return $this->is_readable;
    }

    /**
     * Tests if the group specified: 'owner', 'group', or 'world' has write access
     *  Returns true or false
     *
     * @return  bool
     * @since   1.0
     */
    public function isWriteable()
    {
        $this->is_writeable = $this->filesystem_type->isWriteable();

        return $this->is_writeable;
    }

    /**
     * Tests if the group specified: 'owner', 'group', or 'world' has execute access
     *  Returns true or false
     *
     * @return  null
     * @since   1.0
     */
    public function isExecutable()
    {
        $this->is_executable = $this->filesystem_type->isExecutable();

        return $this->is_executable;
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
     * @param   int     $mode
     *
     * @return  int|null
     * @throws  FileException
     * @since   1.0
     */
    public function chmod($mode)
    {
        if (file_exists($this->path)) {
        } else {

            throw new FileException
            ('Filesystem: chmod method. File does not exist' . $this->path);
        }

        if (in_array($mode, array('0600', '0644', '0755', '0750'))) {
        } else {

            throw new FileException
            ('Filesystem: chmod method. Mode not provided: ' . $mode);
        }

        try {
            chmod($this->path, $mode);

        } catch (Exception $e) {

            throw new FileException
            ('Filesystem: chmod method failed for path ' . $this->path . ' mode: ' . $mode);
        }

        return $mode;
    }

    /**
     * Update the touch time and/or the access time for the directory or file identified in the path
     *
     * @param   int     $time
     * @param   int     $atime
     *
     * @return  int|null
     * @throws  FileException
     * @since   1.0
     */
    public function touch($time, $atime = null)
    {
        if (file_exists($this->path)) {
        } else {

            throw new FileException
            ('Filesystem: setModifiedDate method. File does not exist' . $this->path);
        }

        if ($time == '' || $time === null || $time == 0) {
            $time = time();
        }

        try {

            if (touch($this->path, $time)) {
                echo $this->path . ' modification time has been changed to present time';

            } else {
                echo 'Sorry, could not change modification time of ' . $this->path;
            }

        } catch (Exception $e) {

            throw new FileException
            ('Filesystem: is_readable method failed for ' . $this->path);
        }

        return $time;
    }

    /**
     * Close the Local Connection
     *
     * @return  null
     * @since   1.0
     */
    public function close()
    {
        return;
    }
}
