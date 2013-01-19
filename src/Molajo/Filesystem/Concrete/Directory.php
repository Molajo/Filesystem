<?php
/**
 * Directory Instance for Filesystem
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem\Concrete;

defined ('MOLAJO') or die;

use Molajo\Filesystem\Directory as DirectoryInterface;

/**
 * Directory Instance for Filesystem
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
Class Directory extends Path implements DirectoryInterface
{
    /**
     * Adapter Instance
     *
     * @var    object  Adapter
     * @since  1.0
     */
    protected $adapter;

    /**
     * Construct
     *
     * @param   Adapter     $adapter
     * @param   Filesystem  $filesystem
     * @param   array       $options
     *
     * @since   1.0
     */
    public function __construct (Adapter $adapter, $options = array())
    {
        $this->adapter    = $adapter;
        $this->filesystem = $filesystem;
        $this->options    = $options;

        return;
    }

    /**
     * Retrieves the name of the file specified in the path
     *
     * @param   string  $path
     *
     * @return  null
     * @since   1.0
     */
    public function getName ($path)
    {

    }

    /**
     * Returns a list of files located at path directory
     *
     * @param   string  $path
     * @param   string  $file_name_mask
     * @param   string  $extension
     * @param   bool    $recursive
     *
     * @return  null
     * @since   1.0
     */
    public function getList ($path, $file_name_mask = '', $extension = '', $recursive = true)
    {

    }

    /**
     * Create the folder identified in name for the parent identified in path.
     *
     * @param   string  $path
     * @param   string  $name
     * @param   string  $permission
     *
     * @return  null
     * @since   1.0
     */
    public function create ($path, $name, $permission)
    {

    }
}
