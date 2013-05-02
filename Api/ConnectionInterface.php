<?php
/**
 * Filesystem Connection Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Filesystem\Api;

defined('MOLAJO') or die;

use Molajo\Filesystem\Exception\ConnectionException;

/**
 * Filesystem Connection Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @since     1.0
 * @api
 */
interface ConnectionInterface
{
    /**
     * Connect to the Filesystem
     *
     * @param   array $options
     *
     * @return  $this
     * @since   1.0
     * @throws  ConnectionException
     */
    public function connect($options = array());

    /**
     * Close the Connection
     *
     * @return  $this
     * @since   1.0
     * @throws  ConnectionException
     */
    public function close();
}
