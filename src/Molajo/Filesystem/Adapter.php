<?php
/**
 * Adapter Interface for Filesystem
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem;

defined ('MOLAJO') or die;

/**
 * Describes an adapter instance for the filesystem
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 *
 * Full interface specification:
 *  See https://github.com/Molajo/Filesystem/doc/speifications.md
 */
interface Adapter
{
    /**
     * Get Root for Filesystem
     *
     * @return  null
     * @since   1.0
     */
    function getRoot ();

    /**
     * Set Root for Filesystem
     *
     * @param $root
     *
     * @return  mixed
     * @since   1.0
     */
    function setRoot ($root);

    /**
     * Set the username
     *
     * @param   string  $username
     *
     * @return  mixed
     * @since   1.0
     */
    function setUsername ($username);

    /**
     * Get the username
     *
     * @return  mixed
     * @since   1.0
     */
    function getUsername ();

    /**
     * Set the password
     *
     * @param   string  $password
     *
     * @return  mixed
     * @since   1.0
     */
    function setPassword ($password);

    /**
     * Get the password
     *
     * @return  mixed
     * @since   1.0
     */
    function getPassword ();

    /**
     * Set the Host
     *
     * @param   string  $host
     *
     * @return  mixed
     * @since   1.0
     */
    function setHost ($host);

    /**
     * Get the Host
     *
     * @return  mixed
     * @since   1.0
     */
    function getHost ();

    /**
     * Set the Port
     *
     * @param   int  $port
     *
     * @return  int
     * @since   1.0
     */
    function setPort ($port = 21);

    /**
     * Get the Port
     *
     * @return  mixed
     * @since   1.0
     */
    function getPort ();

    /**
     * Set the Timeout
     *
     * @param   int  $timeout
     *
     * @return  int
     * @since   1.0
     */
    function setTimeout ($timeout = 15);

    /**
     * Get the Timeout
     *
     * @return  int
     * @since   1.0
     */
    function getTimeout ();

    /**
     * Set the Passive Indicator
     *
     * @param   int  $is_passive
     *
     * @return  int
     * @since   1.0
     */
    function setIs_passive ($is_passive = 1);

    /**
     * Get the Passive indicator
     *
     * @return  int
     * @since   1.0
     */
    function getIs_passive ();

    /**
     * Connect to Filesystem
     *
     * @return  null
     * @since   1.0
     */
    function connect ();

    /**
     * Checks to see if the connection is set, returning true, or not, returning false
     *
     * @return  bool
     * @since   1.0
     */
    function isConnected ();

    /**
     * Method to login to a server once connected
     *
     * @return  bool
     * @since   1.0
     * @throws  \RuntimeException
     */
    function login ();

    /**
     * Set the Connection ID
     *
     * @param   object|resource  $connection
     *
     * @return  int
     * @since   1.0
     */
    function setConnection ($connection);

    /**
     * Get the Connection
     *
     * @return  object|resource
     * @since   1.0
     */
    function getConnection ();

    /**
     * Destruct Method
     *
     * @return  void
     * @since   1.0
     */
    function __destruct ();

    /**
     * Close the FTP Connection
     *
     * @return  void
     * @since   1.0
     */
    function close ();
}
