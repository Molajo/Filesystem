<?php
/**
 * System Target Interface for Filesystem Adapter
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem\Adapter;

defined('MOLAJO') or die;

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
     * @return  void
     * @since   1.0
     */
    public function setRoot();

    /**
     * Set persistence indicator for Filesystem
     *
     * @return  bool
     * @since   1.0
     */
    public function setPersistence();

    /**
     * Get Directory Permissions for Filesystem
     *
     * @return  void
     * @since   1.0
     */
    public function setDirectoryDefaultPermissions();

    /**
     * Get File Permissions for Filesystem
     *
     * @return  void
     * @since   1.0
     */
    public function setFileDefaultPermissions();

    /**
     * Get Read Only for Filesystem
     *
     * @return  void
     * @since   1.0
     */
    public function setReadOnly();

    /**
     * Get Root of Filesystem
     *
     * @return  string
     * @since   1.0
     */
    public function getRoot();

    /**
     * Get Persistence indicator
     *
     * @return  bool
     * @since   1.0
     */
    public function getPersistence();

    /**
     * Get Directory Permissions for Filesystem
     *
     * @return  int
     * @since   1.0
     */
    public function getDirectoryDefaultPermissions();

    /**
     * Get File Permissions for Filesystem
     *
     * @return  int
     * @since   1.0
     */
    public function getFileDefaultPermissions();

    /**
     * Get Read Only for Filesystem
     *
     * @return  bool
     * @since   1.0
     */
    public function getReadOnly();
}
