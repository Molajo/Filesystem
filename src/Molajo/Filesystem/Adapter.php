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
 */
interface Adapter
{
    /**
     * Constant Persistence: Temporary Filesystem Storage
     *
     * @var     bool
     *
     * @return  null
     * @since   1.0
     */
    const TEMPORARY = 0;

    /**
     * Constant Persistence: Permanent Filesystem Storage
     *
     * @var    bool
     * @since  1.0
     */
    const PERSISTENT = 1;

    /**
     * Get Options for Filesystem
     *
     * @return  null
     * @since   1.0
     */
    function getOptions ();

    /**
     * Set Options for Filesystem
     *
     * @param   array  $options
     *
     * @return  null
     * @since   1.0
     */
    function setOptions ($options = array());

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
     * @param   string
     *
     * @return  null
     * @since   1.0
     */
    function setRoot ($root);

    /**
     * Set Persistence Setting
     *
     * @param   bool  $persistence
     *
     * @return  null
     * @since   1.0
     */
    function setPersistence ($persistence);

    /**
     * Get Persistence Setting
     *
     * @return  null
     * @since   1.0
     */
    function getPersistence ();

    /**
     * Set the username
     *
     * @param   string  $username
     *
     * @return  null
     * @since   1.0
     */
    function setUsername ($username);

    /**
     * Get the username
     *
     * @return  null
     * @since   1.0
     */
    function getUsername ();

    /**
     * Set the password
     *
     * @param   string  $password
     *
     * @return  null
     * @since   1.0
     */
    function setPassword ($password);

    /**
     * Get the password
     *
     * @return  null
     * @since   1.0
     */
    function getPassword ();

    /**
     * Set the Host
     *
     * @param   string  $host
     *
     * @return  null
     * @since   1.0
     */
    function setHost ($host);

    /**
     * Get the Host
     *
     * @return  null
     * @since   1.0
     */
    function getHost ();

    /**
     * Set the Port
     *
     * @param   int  $port
     *
     * @return  null
     * @since   1.0
     */
    function setPort ($port = 21);

    /**
     * Get the Port
     *
     * @return  null
     * @since   1.0
     */
    function getPort ();

    /**
     * Set the Timeout
     *
     * @param   int  $timeout
     *
     * @return  null
     * @since   1.0
     */
    function setTimeout ($timeout = 15);

    /**
     * Get the Timeout
     *
     * @return  null
     * @since   1.0
     */
    function getTimeout ();

    /**
     * Set the Passive Indicator
     *
     * @param   int  $passive_mode
     *
     * @return  null
     * @since   1.0
     */
    function setPassive_mode ($passive_mode = 1);

    /**
     * Get the Passive indicator
     *
     * @return  null
     * @since   1.0
     */
    function getPassive_mode ();

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
     * @return  null
     * @since   1.0
     */
    function isConnected ();

    /**
     * Method to login to a server once connected
     *
     * @return  null
     * @since   1.0
     * @throws  \RuntimeException
     */
    function login ();

    /**
     * Set the Connection
     *
     * @param   object|resource  $connection
     *
     * @return  null
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
     * @return  null
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
