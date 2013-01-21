<?php
/**
 * Authorisation Interface for Filesystem Adapters
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem\Api;

defined ('MOLAJO') or die;

/**
 * Authorisation Interface for Filesystem Adapters
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
interface AuthorisationInterface
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
     * @param   null  $path
     *
     * @return  bool
     * @since   1.0
     */
    function getOwner ($path);

    /**
     * Returns the group for the file or directory defined in the path
     *
     * @param   null  $path
     *
     * @return  string
     * @since   1.0
     */
    function getGroup ($path);

    /**
     * Returns associative array: 'read', 'update', 'execute' as true or false
     *  for 'owner', 'group', or 'world'
     *
     * @param   null  $path
     *
     * @return  null
     * @since   1.0
     */
    function getPermissions ($path);

    /**
     * Retrieves Create Date for directory or file identified in the path
     *
     * @param   string
     *
     * @return  null
     * @since   1.0
     */
    function getCreateDate ();

    /**
     * Retrieves Last Access Date for directory or file identified in the path
     *
     * @param   string
     *
     * @return  null
     * @since   1.0
     */
    function getAccessDate ();

    /**
     * Retrieves Last Update Date for directory or file identified in the path
     *
     * @param   string
     *
     * @return  null
     * @since   1.0
     */
    function getUpdateDate ();

    /**
     * Tests if the group specified: 'owner', 'group', or 'world' has read access
     *  Returns true or false
     *
     * @param   null    $path
     * @param   null    $group
     *
     * @return  null
     * @since   1.0
     */
    function isReadable ($path, $group = null);

    /**
     * Tests if the group specified: 'owner', 'group', or 'world' has write access
     *  Returns true or false
     *
     * @param   null    $path
     * @param   null    $group
     *
     * @return  null
     * @since   1.0
     */
    function isWriteable ($path, $group = null);

    /**
     * Tests if the group specified: 'owner', 'group', or 'world' has execute access
     *
     * @param   null    $path
     * @param   null    $group
     *
     * @return  null
     * @since   1.0
     */
    function isExecutable ($path, $group = null);

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
     * Using an associative array with three entries: 'read', 'update', 'execute' with a value for
     *  each entry as either 'true' or 'false' for the group specified: 'owner', 'group',  or 'world'
     *
     * @param   string  $path
     * @param   array   $permissions
     *
     * @return  null
     * @since   1.0
     */
    function setPermissions ($path, $permissions = array());

    /**
     * Set read access to true or false for the group specified: 'owner', 'group', or 'world'
     *
     * @param   string  $path
     * @param   null    $group
     * @param   string  $permissions
     *
     * @return  null
     * @since   1.0
     */
    function setReadable ($path, $group = null, $permission = true);

    /**
     * Set write access to true or false for the group specified: 'owner', 'group', or 'world'
     *
     * @param   null    $group
     *
     * @return  null
     * @since   1.0
     */
    function setWriteable ($path, $group = null, $permission = true);

    /**
     * Set execute access to true or false for the group specified: 'owner', 'group', or 'world'
     *
     * @param   null    $group
     *
     * @return  null
     * @since   1.0
     */
    function setExecutable ($path, $group = null, $permission = true);

    /**
     * Sets the Last Access Date for directory or file identified in the path
     *
     * @param   string
     *
     * @return  null
     * @since   1.0
     */
    function setAccessDate ();

    /**
     * Sets the Last Update Date for directory or file identified in the path
     *
     * @param   string  $value
     *
     * @return  null
     * @since   1.0
     */
    function setUpdateDate ($value);
}
