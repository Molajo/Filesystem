<?php
/**
 * System Interface for Filesystem Adapter
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem;

defined ('MOLAJO') or die;

/**
 * System Interface for Filesystem Adapter
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
     * Get the Connection
     *
     * @return  null
     * @since   1.0
     */
    public function getConnection ();

    /**
     * Set the Connection
     *
     * @return  null
     * @since   1.0
     */
    public function setConnection ();

    /**
     * Close the Connection
     *
     * @return  null
     * @since   1.0
     */
    public function close ();

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
     * Get Root of Filesystem
     *
     * @return  string
     * @since   1.0
     */
    public function getRoot ();

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
     * Returns the owner of the file or directory defined in the path
     *
     * @param   string  $path
     *
     * @return  bool
     * @since   1.0
     */
    public function getOwner ($path = '');

    /**
     * Returns the group for the file or directory defined in the path
     *
     * @param   string  $path
     *
     * @return  string
     * @since   1.0
     */
    public function getGroup ($path = '');

    /**
     * Retrieves Create Date for directory or file identified in the path
     *
     * @param   string  $path
     *
     * @return  null
     * @since   1.0
     */
    public function getCreateDate ($path = '');

    /**
     * Retrieves Last Access Date for directory or file identified in the path
     *
     * @param   string  $path
     *
     * @return  null
     * @since   1.0
     */
    public function getAccessDate ($path = '');

    /**
     * Retrieves Last Update Date for directory or file identified in the path
     *
     * @param   string   $path
     *
     * @return  null
     * @since   1.0
     */
    public function getModifiedDate ($path = '');

    /**
     * Tests for read access, returning true or false
     *
     * @param   string  $path
     *
     * @return  null
     * @since   1.0
     */
    public function isReadable ($path = '');

    /**
     * Tests for write access, returning true or false
     *
     * @param   string  $path
     *
     * @return  null
     * @since   1.0
     */
    public function isWriteable ($path = '');

    /**
     * Tests for execute access, returning true or false
     *
     * @param   string  $path
     *
     * @return  null
     * @since   1.0
     */
    public function isExecutable ($path = '');

    /**
     * Change file mode
     *
     * @param   string  $path
     * @param   int     $mode
     *
     * @return  null
     * @since   1.0
     */
    public function chmod ($path = '', $mode);

    /**
     * Update the touch time and/or the access time for the directory or file identified in the path
     *
     * @param   string  $path
     * @param   int     $time
     * @param   int     $atime
     *
     * @return  null
     * @since   1.0
     */
    public function touch ($path = '', $time, $atime = null);

    /**
     * Normalizes the given path
     *
     * @param   $path
     *
     * @return  string
     * @since   1.0
     * @throws  \OutOfBoundsException
     */
    public function normalise ($path = '');
}
