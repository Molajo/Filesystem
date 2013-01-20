<?php
/**
 * Local Adapter for Filesystem
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem\Adapter;

defined ('MOLAJO') or die;

/**
 * Local Adapter for Filesystem
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class Local extends Adapter
{
    /**
     * Constructor
     *
     * @param   array  $options
     *
     * @since   1.0
     */
    public function __construct ($options = array())
    {
        parent::__construct ($options);

        return;
    }

    /**
     * Method to connect to a Local server
     *
     * @return  object|resource
     * @since   1.0
     * @throws  \Exception
     */
    public function connect () {}

    /**
     * Method to login to a server once connected
     *
     * @return  bool
     * @since   1.0
     * @throws  \RuntimeException
     */
    public function login () {}

    /**
     * Destruct Method
     *
     * @return  void
     * @since   1.0
     */
    public function __destruct () {}

    /**
     * Close the Local Connection
     *
     * @return  void
     * @since   1.0
     * @throws  \Exception
     */
    public function close () {}
}
