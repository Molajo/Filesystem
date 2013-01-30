<?php
/**
 * System Target Interface for Filesystem Adapter
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem\Targetinterface;

defined ('MOLAJO') or die;

/**
 * System Target Interface for Filesystem Adapter
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
interface SystemInterface
{
    /**
     * Set Root of Filesystem
     *
     * @param   string  $path
     *
     * @return  string
     * @since   1.0
     */
    public function setRoot ($root);

    /**
     * Set persistence indicator for Filesystem
     *
     * @param   bool  $persistence
     *
     * @return  bool
     * @since   1.0
     */
    public function setPersistence ($persistence);

    /**
     * Get Root of Filesystem
     *
     * @return  string
     * @since   1.0
     */
    public function getRoot ();

    /**
     * Get Persistence indicator
     *
     * @return  string
     * @since   1.0
     */
    public function getPersistence ();
}
