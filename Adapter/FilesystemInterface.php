<?php
/**
 * Filesystem Interface for Adapter
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem\Adapter;

defined('MOLAJO') or die;

/**
 * Filesystem Interface for Adapter
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
interface FilesystemInterface
{
    /**
     * Connect to the Filesystem
     *
     * @param   array  $options
     *
     * @return  void
     * @since   1.0
     */
    public function connect($options = array());

    /**
     * Sets the value of the path defining the current directory and file
     *
     * @param   $path
     *
     * @return  void
     * @since   1.0
     */
    public function setPath($path);

    /**
     * Retrieves and sets metadata for the file specified in path
     *
     * @return  void
     * @since   1.0
     */
    public function getMetadata();

    /**
     * Execute the action requested
     *
     * @param   string  $action
     *
     * @return  void
     * @since   1.0
     */
    public function doAction($action = '');

    /**
     * Close the Connection
     *
     * @return  void
     * @since   1.0
     */
    public function close();
}
