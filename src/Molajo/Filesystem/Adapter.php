<?php
/**
 * Adapter Class
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem;

defined('MOLAJO') or die;

use Molajo\Filesystem\Targetinterface\Filesystem;
use Molajo\Filesystem\Exception\FileException;

/**
 * Adapter Class
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
Class Adapter
{
    /**
     * Filesystem Type Name
     *
     * @var    string
     * @since  1.0
     */
    protected $type;

    /**
     * Filesystem Type Instance
     *
     * @var     string
     * @since   1.0
     */
    protected $filesystem_type;

    /**
     * Filesystem Object and Interface
     *
     * @var     object  Filesystem
     * @since   1.0
     */
    public $filesystem;

    /**
     * Construct
     *
     * @param   string  $type
     * @param   string  $action
     * @param   string  $path
     * @param   array   $options
     *
     * @since   1.0
     * @throws  FileException
     */
    public function __construct($type = 'Local', $action, $path, $options = array())
    {
        /** Get the Filesystem Type */
        $this->type = $type;

        if ($this->type == '') {
            $this->type = 'Local';
        }

        $this->filesystem_type = $this->getType();

        $this->filesystem      = $this->getFilesystem();

        $this->connect();

        $this->path            = $this->setPath($path);

        $this->getMetadata();
        echo '<pre>';
        var_dump($this->filesystem_type);
        echo '</pre>';
        die;
        $this->doAction($action, $options);

        return $this->filesystem_type;
    }

    /**
     * Get the Filesystem Type (ex., Local, Ftp, Virtual, etc.)
     *
     * @return  object  Filesystem Type
     * @since   1.0
     * @throws  FileException
     */
    protected function getType()
    {
        $class = 'Molajo\\Filesystem\\Type\\' . $this->type;

        if (class_exists($class)) {
        } else {
            throw new FileException('Filesystem Type Class ' . $class . ' does not exist.');
        }

        return new $class();
    }

    /**
     * Get Filesystem Object
     *
     * @return  object  Filesystem
     * @since   1.0
     * @throws  FileException
     */
    protected function getFilesystem()
    {
        $class = 'Molajo\\Filesystem\\Targetinterface\\Filesystem';

        if (class_exists($class)) {
        } else {
            throw new FileException('Filesystem Adapter Class Filesystem does not exist.');
        }

        return new Filesystem($this->filesystem_type);
    }

    /**
     * Connect to the Filesystem
     *
     * @return  object  Filesystem
     * @since   1.0
     */
    protected function connect()
    {
        $this->filesystem = $this->filesystem->connect();

        return $this->filesystem;
    }

    /**
     * Set the Path
     *
     * @return  object  Filesystem
     * @since   1.0
     */
    protected function setPath($path)
    {
        $this->path = $this->filesystem->setPath($path);

        return $this->path;
    }

    /**
     * Get Filesystem Metadata
     *
     * @return  object  Filesystem
     * @since   1.0
     */
    public function getMetadata()
    {
        $this->filesystem = $this->filesystem->getMetadata();

        return $this->filesystem;
    }

    /**
     * Perform Requested Filesystem Action
     *
     * @since   1.0
     * @throws  FileException
     */
    public function doAction($action, $options = array())
    {
        if (is_array($options)) {
        } else {
            $options = array();
        }

        $this->filesystem_type = $this->filesystem->$action($options);

        return $this->filesystem_type;
    }
}
