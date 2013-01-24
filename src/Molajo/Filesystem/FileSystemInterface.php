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
interface FilesystemInterface
{
    /**
     * Connect
     *
     * @return  null
     * @since   1.0
     */
    function connect ();

    /**
     * Get the Connection
     *
     * @return  null
     * @since   1.0
     */
    function getConnection ();

    /**
     * Set the Connection
     *
     * @return  null
     * @since   1.0
     */
    function setConnection ();

    /**
     * Close the Connection
     *
     * @return  null
     * @since   1.0
     */
    function close ();

    /**
     * Returns the owner of the file or directory defined in the path
     *
     * @param   string  $path
     *
     * @return  bool
     * @since   1.0
     */
    function getOwner ($path);

    /**
     * Returns the group for the file or directory defined in the path
     *
     * @param   string  $path
     *
     * @return  string
     * @since   1.0
     */
    function getGroup ($path);

    /**
     * Retrieves Create Date for directory or file identified in the path
     *
     * @param   string  $path
     *
     * @return  null
     * @since   1.0
     */
    function getCreateDate ($path);

    /**
     * Retrieves Last Access Date for directory or file identified in the path
     *
     * @param   string  $path
     *
     * @return  null
     * @since   1.0
     */
    function getAccessDate ($path);

    /**
     * Retrieves Last Update Date for directory or file identified in the path
     *
     * @param   string   $path
     *
     * @return  null
     * @since   1.0
     */
    function getUpdateDate ($path);

    /**
     * Tests for read access, returning true or false
     *
     * @param   string  $path
     *
     * @return  null
     * @since   1.0
     */
    function isReadable ($path);

    /**
     * Tests for write access, returning true or false
     *
     * @param   string  $path
     *
     * @return  null
     * @since   1.0
     */
    function isWriteable ($path);

    /**
     * Tests for execute access, returning true or false
     *
     * @param   string  $path
     *
     * @return  null
     * @since   1.0
     */
    function isExecutable ($path);

    /**
     * Changes the owner to the value specified for the file or directory defined in the path
     *
     * @param   string  $path
     * @param   string  $owner
     *
     * @return  string
     * @since   1.0
     */
    function setOwner ($path, $owner);

    /**
     * Changes the group to the value specified for the file or directory defined in the path
     *
     * @param   string  $path
     * @param   string  $group
     *
     * @return  null
     * @since   1.0
     */
    function setGroup ($path, $group);

    /**
     * Set read access to true or false for the group specified: 'owner', 'group', or 'world'
     *
     * @param   string  $path
     * @param   null    $group
     * @param   bool    $permission
     *
     * @return  null
     * @since   1.0
     */
    function setReadable ($path, $group = null, $permission = true);

    /**
     * Set write access to true or false for the group specified: 'owner', 'group', or 'world'
     *
     * @param   string  $path
     * @param   null    $group
     * @param   bool    $permission
     *
     * @return  null
     * @since   1.0
     */
    function setWriteable ($path, $group = null, $permission = true);

    /**
     * Set execute access to true or false for the group specified: 'owner', 'group', or 'world'
     *
     * @param   string  $path
     * @param   null    $group
     * @param   bool    $permission
     *
     * @return  null
     * @since   1.0
     */
    function setExecutable ($path, $group = null, $permission = true);

    /**
     * Sets the Last Access Date for directory or file identified in the path
     *
     * @param   string  $path
     * @param   string  $value
     *
     * @return  null
     * @since   1.0
     */
    function setAccessDate ($path, $value);

    /**
     * Sets the Last Update Date for directory or file identified in the path
     *
     * @param   string  $path
     * @param   string  $value
     *
     * @return  null
     * @since   1.0
     */
    function setUpdateDate ($path, $value);
}
