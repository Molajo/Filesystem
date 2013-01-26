<?php
/**
 * Adapter Class
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem;

defined ('MOLAJO') or die;

use Molajo\Filesystem\Exception\FileException as FileException;

/**
 * Adapter Class
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
Class Adapter implements AdapterInterface
{
    /**
     * Options
     *
     * @var    array  $options
     * @since  1.0
     */
    protected $options = array();

    /**
     * Path
     *
     * @var    string
     * @since  1.0
     */
    protected $path;

    /**
     * Adapter Name
     *
     * @var    string
     * @since  1.0
     */
    protected $adapter_name;

    /**
     * Adapter Instance
     *
     * @var    string
     * @since  1.0
     */
    protected $adapter;

    /**
     * Construct
     *
     * @param   string  $path
     * @param   array   $options
     *
     * @since   1.0
     * @throws  FileException
     */
    public function __construct ($path, $options = array())
    {
        $this->path = $path;

        $this->adapter_name = '';
        if (isset($this->options['adapter_name'])) {
            $this->adapter_name = $this->options['adapter_name'];
        }

        if ($this->adapter_name == '') {
            $this->adapter_name = 'Local';
        }

        $this->options = $options;

        $this->setAdapter ($this->adapter_name, $this->path);

        return $this;
    }

    /**
     * Set Adapter
     *
     * @param   string  $adapter_name
     * @param   string  $path
     *
     * @return  object  $adapter
     * @since   1.0
     * @throws  FileException
     */
    public function setAdapter ($adapter_name = '', $path = '')
    {
        if ($adapter_name == '') {
            $adapter_name = $this->adapter_name;
        }

        $this->adapter_name = $adapter_name;

        if ($path == '') {
            $path = $this->path;
        }

        $this->path = $path;

        $class = 'Molajo\\Filesystem\\Adapter\\' . $this->adapter_name;

        if (class_exists ($class)) {
        } else {
            throw new FileException('Filesystem Adapter Class ' . $class . ' does not exist.');
        }

        $this->adapter = new $class($path, $this->options);

        return $this->adapter;
    }

    /**
     * Get Adapter
     *
     * @return  string
     * @since   1.0
     * @throws  FileException
     */
    public function getAdapter ()
    {
        return $this->adapter;
    }
}
