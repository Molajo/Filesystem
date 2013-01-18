<?php
/**
 * Filesystem
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem\Concrete;

use Molajo\Filesystem\Filesystem as FilesystemInterface;

defined ('MOLAJO') or die;

/**
 * Filesystem
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 *
 * Full interface specification:
 *  See https://github.com/Molajo/Filesystem/doc/speifications.md
 */
abstract class Filesystem implements FilesystemInterface
{
    /**
     * Filesystem Name
     *
     * @var    string
     * @since  1.0
     */
    protected $name;

    /**
     * Adapter injected into Filesystem
     *
     * @var    string
     * @since  1.0
     */
    protected $adapter;

    /**
     * $options
     *
     * @var    string
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
     * Constant Temporary Filesystem Storage
     *
     * @var    string
     * @since  1.0
     */
    const TEMPORARY = 0;

    /**
     * Constant Permanent Filesystem Storage
     *
     * @var    string
     * @since  1.0
     */
    const PERSISTENT = 1;

    /**
     * Construct
     *
     * @return  void
     * @since   1.0
     */
    public function __construct (Adapter $adapter, $root, $options = array())
    {
        $this->options = $options;

        if (isset($this->options['name'])) {
            $this->name = $this->options['name'];
        }

        if (isset($this->options['root'])) {
            $this->root = $this->options['root'];
        }

        $this->adapter = $adapter;

        return;
    }

    /**
     * Retrieve the name of the Filesystem
     *
     * @return  null
     * @since   1.0
     */
    public function getName ()
    {

    }

    /**
     * Returns the adapter
     *
     * @return Adapter
     */
    public function getAdapter ()
    {
        return $this->adapter;
    }

    /**
     * Returns the adapter
     *
     * @return Adapter
     */
    public function setAdapter ($adapter)
    {
        return $this->adapter = $adapter;
    }

    /**
     * Get root of the filesystem
     *
     * @return  null
     * @since   1.0
     */
    public function getRoot ()
    {
        return $this->root;
    }

    /**
     * Set root of the filesystem
     *
     * @param   $root
     *
     * @return  mixed
     * @since   1.0
     */
    public function setRoot ($root)
    {
        $this->root = $root;
    }
}
