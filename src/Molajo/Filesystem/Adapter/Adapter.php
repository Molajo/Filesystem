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

use Molajo\Filesystem\Adapter as AdapterInterface;

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
     * Username
     *
     * @var    string
     * @since  1.0
     */
    protected $username;

    /**
     * Password
     *
     * @var    string
     * @since  1.0
     */
    protected $password;

    /**
     * Host
     *
     * @var    string
     * @since  1.0
     */
    protected $host;

    /**
     * Port
     *
     * @var    string
     * @since  1.0
     */
    protected $port;

    /**
     * Timeout in minutes
     *
     * @var    string
     * @since  1.0
     */
    protected $timeout = 15;

    /**
     * Passive Mode
     *
     * @var    bool
     * @since  1.0
     */
    protected $is_passive = false;

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
        if (isset($this->options['username'])) {
            $this->username = $this->options['username'];
        } else {
            $this->username = '';
        }

        if (isset($this->options['password'])) {
            $this->password = $this->options['password'];
        } else {
            $this->password = '';
        }

        if (isset($this->options['host'])) {
            $this->host = $this->options['host'];
        } else {
            $this->host = '127.0.0.1';
        }

        if (isset($this->options['port'])) {
            $this->port = $this->options['port'];
        } else {
            $this->port = 0;
        }

        if (isset($this->options['root'])) {
            $this->root = $this->options['root'];
        } else {
            $this->root = '/';
        }

        if (isset($this->options['timeout'])) {
            $this->timeout = $this->options['timeout'];
        } else {
            $this->timeout = 15;
        }

        if (isset($this->options['is_passive'])) {
            $this->is_passive = $this->options['is_passive'];
        } else {
            $this->is_passive = false;
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
        if (is_array($options)) {
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
        return $this->root = $root;
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
     * Set the username
     *
     * @param   string  $username
     *
     * @return  mixed
     * @since   1.0
     */
    public function setUsername ($username)
    {
        return $this->username = $username;
    }

    /**
     * Get the username
     *
     * @return  mixed
     * @since   1.0
     */
    public function getUsername ()
    {
        return $this->username;
    }

    /**
     * Set the password
     *
     * @param   string  $password
     *
     * @return  mixed
     * @since   1.0
     */
    public function setPassword ($password)
    {
        return $this->password = $password;
    }

    /**
     * Get the password
     *
     * @return  mixed
     * @since   1.0
     */
    public function getPassword ()
    {
        return $this->password;
    }

    /**
     * Set the Host
     *
     * @param   string  $host
     *
     * @return  mixed
     * @since   1.0
     */
    public function setHost ($host)
    {
        return $this->host = $host;
    }

    /**
     * Get the Host
     *
     * @return  mixed
     * @since   1.0
     */
    public function getHost ()
    {
        return $this->host;
    }

    /**
     * Set the Port
     *
     * @param   int  $port
     *
     * @return  int
     * @since   1.0
     */
    public function setPort ($port = 21)
    {
        return $this->port = $port;
    }

    /**
     * Get the Port
     *
     * @return  mixed
     * @since   1.0
     */
    public function getPort ()
    {
        return $this->port;
    }

    /**
     * Set the Timeout
     *
     * @param   int  $timeout
     *
     * @return  int
     * @since   1.0
     */
    public function setTimeout ($timeout = 15)
    {
        return $this->timeout = (int)$timeout;
    }

    /**
     * Get the Timeout
     *
     * @return  int
     * @since   1.0
     */
    public function getTimeout ()
    {
        return (int)$this->timeout;
    }

    /**
     * Set the Passive Indicator
     *
     * @param   int  $is_passive
     *
     * @return  int
     * @since   1.0
     */
    public function setIs_passive ($is_passive = 1)
    {
        $this->is_passive = $is_passive;

        if ((int)$this->is_passive == 0) {
        } else {
            $this->is_passive = 1;
        }

        return $this->is_passive;
    }

    /**
     * Get the Passive indicator
     *
     * @return  int
     * @since   1.0
     */
    public function getIs_passive ()
    {
        if ((int)$this->is_passive == 0) {
        } else {
            $this->is_passive = 1;
        }

        return (int)$this->is_passive;
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
     * Method to login to a server once connected
     *
     * @return  bool
     * @since   1.0
     * @throws  \RuntimeException
     */
    public function login ()
    {

    }

    /**
     * Destruct Method
     *
     * @return  void
     * @since   1.0
     */
    public function __destruct ()
    {
        if (is_resource ($this->connection)) {
            $this->close ();
        }

        return;
    }

    /**
     * Close the FTP Connection
     *
     * @return  void
     * @since   1.0
     */
    public function close ()
    {
        if ($this->isConnected ()) {
            $this->connection = null;
        }

        return;
    }
}
