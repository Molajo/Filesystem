<?php
/**
 * Filesystem Adapter Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Filesystem\Adapter;

defined('MOLAJO') or die;

/**
 * Filesystem Adapter Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @since     1.0
 */
interface AdapterInterface
{
    /**
     * Connect to the Filesystem
     *
     * @param   array $options
     *
     * @return  $this
     * @since   1.0
     */
    public function connect($options = array());

    /**
     * Sets the value of the path defining the current directory and file
     *
     * @param   string $path
     *
     * @return  $this
     * @since   1.0
     */
    public function setPath($path);

    /**
     * Retrieves and sets metadata for the file specified in path
     *
     * @return  $this
     * @since   1.0
     */
    public function getMetadata();

    /**
     * Execute the action requested
     *
     * @param   string $action
     *
     * @return  $this
     * @since   1.0
     */
    public function doAction($action = '');

    /**
     * Close the Connection
     *
     * @return  $this
     * @since   1.0
     */
    public function close();
}
