<?php
/**
 * FTP Adapter for Filesystem
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem\Adapter;

defined ('MOLAJO') or die;

/**
 * FTP Adapter for Filesystem
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class Ftp extends Adapter
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
     * Method to connect to a FTP server
     *
     * @return  object|resource
     * @since   1.0
     * @throws  \Exception
     */
    public function connect ()
    {
        if ($this->isConnected ()) {
            return $this->connection;
        }

        try {
            $id = \ftp_connect ($this->host, $this->port, $this->timeout);
            $this->setConnection ($id);

        } catch (\Exception $e) {

            throw new \Exception
            ('Filesystem Adapter FTP: Unable to connect to the FTP Server '
                . ' Host: ' . $this->host . ' Port: ' . $this->port);
        }

        if ($this->connection === false) {
            throw new \Exception
            ('Filesystem Adapter FTP: Unable to set passive mode for FTP Server '
                . ' Host: ' . $this->host . ' Port: ' . $this->port);
        }

        try {
            \ftp_pasv ($this->connection, $this->getPassive_mode ());

        } catch (\Exception $e) {

            throw new \Exception
            ('Filesystem Adapter FTP: Unable to set passive mode for FTP Server '
                . ' Host: ' . $this->host . ' Port: ' . $this->port);
        }

        try {
            $this->login ();

        } catch (\Exception $e) {

            throw new \Exception
            ('Filesystem Adapter FTP: Login failed for User: ' . $this->username);
        }

        try {
            $results = ftp_chdir ($this->connection, $this->root);

        } catch (\Exception $e) {

            throw new \Exception
            ('Filesystem Adapter FTP: Changing FTP Directory failed. Directory: ' . $this->root);
        }

        if ($results === false) {
            throw new \Exception
            ('Filesystem Adapter FTP: Unable to change directory: '
                . $this->root . ' for FTP Server '
                . ' Host: ' . $this->host . ' Port: ' . $this->port);
        }

        return $this->connection;
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
        $logged_in = ftp_login (
            $this->getConnection (),
            $this->getUsername (),
            $this->getPassword ()
        );

        if ($logged_in === true) {
        } else {
            throw new \RuntimeException
            ('Filesystem Adapter FTP: Unable to login with Username: ' . $this->getUsername ());
        }

        return true;
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
     * @throws  \Exception
     */
    public function close ()
    {
        if ($this->isConnected ()) {
            try {
                ftp_close ($this->connection);

            } catch (\Exception $e) {

                throw new \Exception
                ('Filesystem Adapter FTP: Closing FTP Connection Failed');
            }
        }

        return;
    }
}
