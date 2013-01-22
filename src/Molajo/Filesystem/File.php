<?php
/**
 * File Class
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem;

defined ('MOLAJO') or die;

use RuntimeException;
use Molajo\Filesystem\Exception\FileException as FileException;

/**
 * File Class
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
Class File
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
     * Absolute path
     *
     * An absolute path is a relative path from the root directory, prepended with a '/'.
     *
     * @var    string
     * @since  1.0
     */
    protected $absolute_path;

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
     * @var    FilesystemAdapter
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
     * @throws  Exception\FileException
     * @throws  RuntimeException
     */
    public function __construct ($path, $options = array())
    {
        $this->options      = $options;
        $path               = $this->setPath ($path);
        $this->adapter_name = '';

        if (isset($this->options['adapter_name'])) {
            $this->adapter_name = $this->options['adapter_name'];
        }

        if ($this->adapter_name == '') {
            $this->adapter_name = 'Local';
        }

        $class = 'Molajo\\Filesystem\\Adapter\\' . $this->adapter_name;

        if (class_exists ($class)) {
        } else {
            throw new RuntimeException('Filesystem Adapter Class ' . $class . ' does not exist.');
        }

        $this->adapter = new $class($path, $this->options);

        return;
    }

    /**
     * Set the Path
     *
     * @param   string  $path
     *
     * @return  string
     * @since   1.0
     * @throws  Exception\FileException
     */
    public function setPath ($path)
    {
        $path = $this->normalise ($path);

        if ($this->exists ($path) === false) {
            throw new FileException('Filesystem File Class: Path ' . $path . ' does not exist.');
        }

        $this->getAbsolutePath ($path);

        $this->path = $path;

        return $this->path;
    }

    /**
     * Does the path exist (either as a file or a folder)?
     *
     * @param string $path
     *
     * @return bool|null
     */
    public function exists ($path)
    {
        if (file_exists ($path)) {
            return true;
        }

        return false;
    }

    /**
     * Entry point for calls to be passed through to the adapter class
     *
     * @param   string  $name
     * @param   array   $arguments
     *
     * @return  object
     * @since   1.0
     */
    public function __call ($name, $arguments)
    {
        return $this->adapter->$name(implode ($arguments));
    }

    /**
     * Retrieves the absolute path, which is the relative path from the root directory,
     *  prepended with an '/'.
     *
     * @param   string  $path
     *
     * @return  string
     * @since   1.0
     */
    public function getAbsolutePath ($path)
    {
        $this->absolute_path = realpath ($path);

        return $this->absolute_path;
    }

    /**
     * Normalizes the given path
     *
     * @param   $path
     *
     * @return  string
     * @since   1.0
     */
    public function normalise ($path)
    {
        $absolute_path = false;
        if (substr ($path, 0, 1) == '/') {
            $absolute_path = true;
            $path          = substr ($path, 1, strlen ($path));
        }

        /** Unescape slashes */
        $path = str_replace ('\\', '/', $path);

        /**  Filter: empty value
         *
         * @link http://tinyurl.com/arrayFilterStrlen
         */
        $nodes = array_filter (explode ('/', $path), 'strlen');

        $normalised = array();

        foreach ($nodes as $node) {

            /** '.' means current - ignore it      */
            if ($node == '.') {

                /** '..' is parent - remove the parent */
            } elseif ($node == '..') {

                if (count ($normalised) > 0) {
                    array_pop ($normalised);
                }

            } else {
                $normalised[] = $node;
            }

        }

        $path = implode ('/', $normalised);
        if ($absolute_path === true) {
            $path = '/' . $path;
        }

        return $path;
    }
}
