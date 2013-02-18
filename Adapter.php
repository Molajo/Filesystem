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

use Molajo\Filesystem\Adapter\AdapterInterface;
use Molajo\Filesystem\Exception\FilesystemException;

/**
 * Adapter for Filesystem
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @since     1.0
 */
Class Adapter implements AdapterInterface
{
    /**
     * Filesystem Type
     *
     * @var     object
     * @since   1.0
     */
    public $fs;

    /**
     * Construct
     *
     * @param string $action
     * @param string $path
     * @param string $filesystem_type
     * @param array  $options
     *
     * @since   1.0
     * @throws FilesystemException
     */
    public function __construct($action = '', $path = '', $filesystem_type = 'Local', $options = array())
    {
        if ($filesystem_type == '') {
            $filesystem_type = 'Local';
        }
        $this->getFilesystemType($filesystem_type);

        $this->connect($options);

        if ($path == '') {
            throw new FilesystemException
            ('Filesystem Path is required, but was not provided.');
        }
        $this->setPath($path);

        $this->getMetadata();

        $this->doAction($action);

        $this->close();

        return $this->fs;
    }

    /**
     * Get the Filesystem Type (ex., Local, Ftp, etc.)
     *
     * @param string $filesystem_type
     *
     * @return void
     * @since   1.0
     * @throws FilesystemException
     */
    protected function getFilesystemType($filesystem_type)
    {
        $class = 'Molajo\\Filesystem\\Type\\' . $filesystem_type;

        if (class_exists($class)) {
        } else {
            throw new FilesystemException
            ('Filesystem Type Class ' . $class . ' does not exist.');
        }

        $this->fs = new $class($filesystem_type);

        return;
    }

    /**
     * Connect to the Filesystem Type
     *
     * @param array $options
     *
     * @return void
     * @since   1.0
     */
    public function connect($options = array())
    {
        $this->fs->connect($options);

        return;
    }

    /**
     * Set the Path
     *
     * @param string $path
     *
     * @return void
     * @since   1.0
     */
    public function setPath($path)
    {
        $this->fs->setPath($path);

        return;
    }

    /**
     * Retrieves and set metadata for the file specified in path
     *
     * @return void
     * @since   1.0
     */
    public function getMetadata()
    {
        $this->fs->getMetadata();

        return;
    }

    /**
     * Execute the action requested
     *
     * @param string $action
     *
     * @return void
     * @since   1.0
     * @throws Exception\FilesystemException
     */
    public function doAction($action = '')
    {
        $this->fs->doAction($action);

        return;
    }

    /**
     * Close the Connection
     *
     * @return void
     * @since   1.0
     */
    public function close()
    {
        $this->fs->close();
    }
}
