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

}
