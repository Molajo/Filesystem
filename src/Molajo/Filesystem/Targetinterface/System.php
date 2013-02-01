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
    public $fs_type;

    /**
     * Adaptee Filesystem Name
     *
     * @var    array
     * @since  1.0
     */
    public $filesystem_type;

    /**
     * Adaptee Filesystem Connection
     *
     * @var    array
     * @since  1.0
     */
    public $connection;

    /**
     * Root Directory for Filesystem Type
     *
     * @var    string
     * @since  1.0
     */
    public $root;

    /**
     * Persistence of Filesystem Type
     *
     * @var    bool
     * @since  1.0
     */
    public $persistence;

    /**
     * Directory Permissions
     *
     * @var    string
     * @since  1.0
     */
    public $default_directory_permissions;

    /**
     * File Permissions
     *
     * @var    string
     * @since  1.0
     */
    public $default_file_permissions;

    /**
     * Read only
     *
     * @var    string
     * @since  1.0
     */
    public $read_only;

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
     * Size
     *
     * @var    string
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
     * @var    date
     * @since  1.0
     */
    public $create_date;

    /**
     * Access Date
     *
     * @var    date
     * @since  1.0
     */
    public $access_date;

    /**
     * Modified Date
     *
     * @var    date
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
     * Mimetype
     *
     * @var    string
     * @since  1.0
     */
    public $mime_type;


    /**
     * Action Results
     *
     * @var    string
     * @since  1.0
     */
    public $action_results;

    /**
     * Constructor
     *
     * @since   1.0
     */
    public function __construct()
    {
        return $this;
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
        $this->root = $this->fs_type->setRoot($root);

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
        $this->persistence = $this->fs_type->setPersistence($persistence);

        return $this->persistence;
    }

    /**
     * Get Directory Permissions for Filesystem
     *
     * @param   $default_directory_permissions
     *
     * @return  bool
     * @since   1.0
     */
    public function setDirectoryPermissions($default_directory_permissions)
    {
        $this->default_directory_permissions = $this->fs_type->setDirectoryPermissions($default_directory_permissions);

        return $this->default_directory_permissions;
    }

    /**
     * Get File Permissions for Filesystem
     *
     * @param   $default_file_permissions
     *
     * @return  bool
     * @since   1.0
     */
    public function setFilePermissions($default_file_permissions)
    {
        $this->default_file_permissions = $this->fs_type->setFilePermissions($default_file_permissions);

        return $this->default_file_permissions;
    }

    /**
     * Get Read Only for Filesystem
     *
     * @param   $read_only
     *
     * @return  bool
     * @since   1.0
     */
    public function setReadOnly($read_only)
    {
        $this->read_only = $this->fs_type->getReadOnly($read_only);

        return $this->read_only;
    }

    /**
     * Get Filesystem Type
     *
     * @return  string
     * @since   1.0
     */
    public function getFilesystemType()
    {
        $this->filesystem_type = $this->fs_type->getFilesystemType();

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
        $this->root = $this->fs_type->getRoot();

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
        $this->persistence = $this->fs_type->getPersistence();

        return $this->persistence;
    }

    /**
     * Get Directory Permissions for Filesystem
     *
     * @return  bool
     * @since   1.0
     */
    public function getDirectoryPermissions()
    {
        $this->default_directory_permissions = $this->fs_type->getDirectoryPermissions();

        return $this->default_directory_permissions;
    }

    /**
     * Get File Permissions for Filesystem
     *
     * @return  bool
     * @since   1.0
     */
    public function getFilePermissions()
    {
        $this->default_file_permissions = $this->fs_type->getFilePermissions();

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
        $this->read_only = $this->fs_type->getReadOnly();

        return $this->read_only;
    }
}
