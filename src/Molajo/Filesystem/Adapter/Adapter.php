<?php
/**
 * Adapter for Filesystem
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem\Adapter;

defined ('MOLAJO') or die;

use Molajo\Filesystem\Api\Adapter as AdapterInterface;

/**
 * Describes Adapter Instance
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
abstract class Adapter implements AdapterInterface
{
    /**
     * Options
     *
     * @var    array
     * @since  1.0
     */
    protected $options;

    /**
     * Root Directory for Filesystem
     *
     * @var    string
     * @since  1.0
     */
    protected $root;

    /**
     * Persistence
     *
     * @var    bool
     * @since  1.0
     */
    protected $persistence;

    /**
     * Connection
     *
     * @var    object|resource
     * @since  1.0
     */
    protected $connection;

    /**
     * Constructor
     *
     * @param   array  $options
     *
     * @since   1.0
     */
    public function __construct ($options = array())
    {
        $this->setOptions ($options);

        if (isset($this->options['root'])) {
            $this->setRoot ($this->options['root']);
        } else {
            $this->setRoot ('/');
        }

        if (isset($this->options['persistence'])) {
            $this->setPersistence ($this->options['persistence']);
        } else {
            $this->setPersistence (0);
        }

        return;
    }

    /**
     * Set the Options
     *
     * @param   array  $options
     *
     * @return  array
     * @since   1.0
     */
    public function setOptions ($options = array())
    {
        if (is_array ($options)) {
            return $this->options = $options;
        }

        return $this->options = array();
    }

    /**
     * Get the Options
     *
     * @return  array  $options
     * @since   1.0
     */
    public function getOptions ()
    {
        return $this->options;
    }

    /**
     * Get Root of Filesystem
     *
     * @return  null
     * @since   1.0
     */
    public function getRoot ()
    {
        return $this->root;
    }

    /**
     * Set Root of Filesystem
     *
     * @param   string  $root
     *
     * @return  mixed
     * @since   1.0
     */
    public function setRoot ($root)
    {
        return $this->root = rtrim ($root, '/\\') . '/';
    }

    /**
     * Set persistence indicator for Filesystem
     *
     * @param   bool  $persistence
     *
     * @return  null
     * @since   1.0
     */
    public function setPersistence ($persistence)
    {
        return $this->persistence = $persistence;
    }

    /**
     * Get persistence indicator for Filesystem
     *
     * @return  null
     * @since   1.0
     */
    public function getPersistence ()
    {
        return $this->persistence;
    }


    /**
     * Connect to Filesystem
     *
     * @return  null
     * @since   1.0
     */
    public function connect ()
    {
    }

    /**
     * Checks to see if the connection is set, returning true, or not, returning false
     *
     * @return  bool
     * @since   1.0
     */
    public function isConnected ()
    {
        try {
            $connected = is_resource ($this->connection);

        } catch (\Exception $e) {
            return false;
        }

        if ($connected === true) {
            return true;
        }

        return false;
    }

    /**
     * Set the Connection
     *
     * @param   object|resource  $connection
     *
     * @return  int
     * @since   1.0
     */
    public function setConnection ($connection)
    {
        return $this->connection = $connection;
    }

    /**
     * Return the connected FTP connection
     *
     * @return  object  resource
     * @since   1.0
     */
    public function getConnection ()
    {
        if ($this->isConnected ()) {
        } else {
            $this->connect ();
        }

        return $this->connection;
    }

    /**
     * Close the FTP Connection
     *
     * @return  void
     * @since   1.0
     */
    public function close ()
    {

    }
}
