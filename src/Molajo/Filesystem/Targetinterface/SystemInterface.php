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
     * Connect
     *
     * @return  null
     * @since   1.0
     */
    public function connect ();

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

    /**
     * Returns the owner of the file or directory defined in the path
     *
     * @return  bool
     * @since   1.0
     */
    public function getOwner ();

    /**
     * Returns the group for the file or directory defined in the path
     *
     * @return  string
     * @since   1.0
     */
    public function getGroup ();

    /**
     * Retrieves Create Date for directory or file identified in the path
     *
     * @return  null
     * @since   1.0
     */
    public function getCreateDate ();

    /**
     * Retrieves Last Access Date for directory or file identified in the path
     *
     * @return  null
     * @since   1.0
     */
    public function getAccessDate ();

    /**
     * Retrieves Last Update Date for directory or file identified in the path
     *
     * @return  null
     * @since   1.0
     */
    public function getModifiedDate ();

    /**
     * Tests for read access, returning true or false
     *
     * @return  null
     * @since   1.0
     */
    public function isReadable ();

    /**
     * Tests for write access, returning true or false
     *
     * @return  null
     * @since   1.0
     */
    public function isWriteable ();

    /**
     * Tests for execute access, returning true or false
     *
     * @return  null
     * @since   1.0
     */
    public function isExecutable ();

    /**
     * Change file mode
     *
     * @param   int     $mode
     *
     * @return  null
     * @since   1.0
     */
    public function chmod ($mode);

    /**
     * Update the touch time and/or the access time for the directory or file identified in the path
     *
     * @param   int     $time
     * @param   int     $atime
     *
     * @return  null
     * @since   1.0
     */
    public function touch ($time, $atime = null);

    /**
     * Close the Connection
     *
     * @return  null
     * @since   1.0
     */
    public function close ();
}
