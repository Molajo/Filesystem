<?php
/**
 * Adapter Interface for Filesystem
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem\Api;

defined ('MOLAJO') or die;

/**
 * Describes an adapter instance for the filesystem
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
interface Adapter
{
    /**
     * Connect
     *
     * @return  null
     * @since   1.0
     */
    function connect ();

    /**
     * Close the FTP Connection
     *
     * @return  null
     * @since   1.0
     */
    function close ();

    /**
     * Get the Connection
     *
     * @return  null
     * @since   1.0
     */
    function getConnection ();
}
