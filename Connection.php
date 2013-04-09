<?php
/**
 * Adapter for Filesystem
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Filesystem;

defined('MOLAJO') or die;

use Exception;
use Molajo\Filesystem\Exception\FilesystemException;
use Molajo\Filesystem\Api\ConnectionInterface;

/**
 * Connect to Filesystem
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @since     1.0
 */
class Connection implements ConnectionInterface
{
    /**
     * Filesystem Type
     *
     * @var     object
     * @since   1.0
     */
    public $fsType;

    /**
     * Adapter
     *
     * @var     object
     * @since   1.0
     */
    public $adapter;

    /**
     * Constructor
     *
     * @param   string $filesystem_type
     * @param   array  $options
     *
     * @since   1.0
     * @api
     */
    public function __construct($filesystem_type = 'Local', $options = array())
    {
        if ($filesystem_type == '') {
            $filesystem_type = 'Local';
        }

        $this->getFilesystemType($filesystem_type);

        $this->getFilesystemAdapter($filesystem_type);

        $this->connect($options);
    }

    /**
     * Entry point for adapter
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
     * Get the Filesystem specific Adapter
     *
     * @param   string $filesystem_type
     *
     * @return  $this
     * @since   1.0
     * @throws  FilesystemException
     * @api
     */
    protected function getFilesystemType($filesystem_type = 'Local')
    {
        $class = 'Molajo\\Filesystem\\Adapter\\' . $filesystem_type;

        try {

            $this->fsType = new $class($filesystem_type);

        } catch (Exception $e) {

            throw new FilesystemException
            ('Filesystem: Could not instantiate Filesystem Type: ' . $filesystem_type);
        }

        return;
    }

    /**
     * Get Adapter, inject with specific Filesystem Adapter
     *
     * @param   string $filesystem_type
     *
     * @return  $this
     * @since   1.0
     * @throws  FilesystemException
     * @api
     */
    protected function getFilesystemAdapter($filesystem_type)
    {
        $class = 'Molajo\\Filesystem\\Adapter';

        try {

            $this->adapter = new $class($this->fsType);

        } catch (Exception $e) {

            throw new FilesystemException
            ('Filesystem: Could not instantiate Adapter for Filesystem Type: ' . $filesystem_type);
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
     * @throws  FilesystemException
     * @api
     */
    public function connect($options = array())
    {
        try {
            $this->adapter->connect($options);

        } catch (Exception $e) {

            throw new FilesystemException
            ('Filesystem: Caught Exception: ' . $e->GetMessage());
        }

        return $this;
    }

    /**
     * Close the Connection
     *
     * @return  $this
     * @since   1.0
     * @throws  FilesystemException
     * @api
     */
    public function close()
    {
        $this->adapter->close();

        return $this;
    }
}
