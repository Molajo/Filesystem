<?php
/**
 * Connection to Filesystem Adapter
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Filesystem;

defined('MOLAJO') or die;

use Exception;
use Molajo\Filesystem\Exception\AdapterException;
use Molajo\Filesystem\Api\ConnectionInterface;

/**
 * Connection to Filesystem Adapter
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @since     1.0
 */
class Connection implements ConnectionInterface
{
    /**
     * Adapter Instance
     *
     * @var     object
     * @since   1.0
     */
    public $adapter;

    /**
     * Adapter Handler Instance
     *
     * @var     object
     * @since   1.0
     */
    public $adapter_handler;

    /**
     * Constructor
     *
     * @param   string $adapter_handler
     * @param   array  $options
     *
     * @since   1.0
     * @api
     */
    public function __construct($adapter_handler = 'Local', $options = array())
    {
        if ($adapter_handler == '') {
            $adapter_handler = 'Local';
        }

        $this->getAdapterHandler($adapter_handler);

        $this->getAdapter($adapter_handler);

        $this->connect($options);
    }

    /**
     * Pass Adapter Calls through to the Adapter Class
     *
     * @param   string $name
     * @param   array  $arguments
     *
     * @return  object
     * @since   1.0
     */
    public function __call($name, $arguments)
    {
        return $this->adapter->$name(implode(', ', $arguments));
    }

    /**
     * Get the Filesystem specific Adapter Handler
     *
     * @param   string $adapter_handler
     *
     * @return  $this
     * @since   1.0
     * @throws  AdapterException
     * @api
     */
    protected function getAdapterHandler($adapter_handler = 'Local')
    {
        $class = 'Molajo\\Filesystem\\Handler\\' . $adapter_handler;

        try {
            $this->adapter_handler = new $class($adapter_handler);

        } catch (Exception $e) {
            throw new AdapterException
            ('Filesystem: Could not instantiate Filesystem Adapter Handler: ' . $adapter_handler);
        }

        return;
    }

    /**
     * Get Filesystem Adapter, inject with specific Filesystem Adapter Handler
     *
     * @param   string $adapter_handler
     *
     * @return  $this
     * @since   1.0
     * @throws  AdapterException
     * @api
     */
    protected function getAdapter($adapter_handler)
    {
        $class = 'Molajo\\Filesystem\\Adapter';

        try {
            $this->adapter = new $class($this->adapter_handler);

        } catch (Exception $e) {
            throw new AdapterException
            ('Filesystem: Could not instantiate Adapter for Filesystem Type: ' . $adapter_handler);
        }

        return $this;
    }

    /**
     * Connect to the Filesystem Type
     *
     * @param   array $options
     *
     * @return  $this
     * @since   1.0
     * @throws  AdapterException
     * @api
     */
    public function connect($options = array())
    {
        try {
            $this->adapter->connect($options);

        } catch (Exception $e) {

            throw new AdapterException
            ('Filesystem: Caught Exception: ' . $e->GetMessage());
        }

        return $this;
    }

    /**
     * Close the Connection
     *
     * @return  $this
     * @since   1.0
     * @throws  AdapterException
     * @api
     */
    public function close()
    {
        $this->adapter->close();

        return $this;
    }
}
