<?php
/**
 * Local Filesystem Type
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Filesystem\Type;

defined('MOLAJO') or die;

/**
 * Local Filesystem
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class Local extends FilesystemType
{
    /**
     * Class constructor
     *
     * @since  1.0
     */
    public function __construct($filesystem_type)
    {
        parent::__construct($filesystem_type);

        $this->setFilesystemType('Local');

        return $this;
    }

    /**
     * Adapter Interface Step 1:
     *
     * Method to connect to a Local server
     *
     * @param   array $options
     *
     * @return  void
     * @since   1.0
     */
    public function connect($options = array())
    {
        parent::connect($options);

        return;
    }

    /**
     * Adapter Interface Step 2:
     *
     * Set the Path
     *
     * @param string $path
     *
     * @return string
     * @since   1.0
     */
    public function setPath($path)
    {
        return parent::setPath($path);
    }

    /**
     * Adapter Interface Step 3:
     *
     * Retrieves and sets metadata for the file specified in path
     *
     * @return  void
     * @since   1.0
     */
    public function getMetadata()
    {
        parent::getMetadata();

        return;
    }

    /**
     * Adapter Interface Step 4:
     *
     * Execute the action requested
     *
     * @param string $action
     *
     * @return void
     * @since   1.0
     */
    public function doAction($action = '')
    {
        parent::doAction($action);

        return;
    }

    /**
     * Close the Local Connection
     *
     * @return void
     * @since   1.0
     */
    public function close()
    {
        return;
    }
}
