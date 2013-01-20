<?php
/**
 * Path Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem\Api;

defined ('MOLAJO') or die;

/**
 * Describes an entry instance for the filesystem
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
interface Path extends Adapter
{
    /**
     * Determine if the file or directory specified in path exists
     *
     * @return  null
     * @since   1.0
     */
    function exists ();

    /**
     * Indicates whether the given path is absolute or not
     *
     * @return  null
     * @since   1.0
     */
    function isAbsolute ();

    /**
     * Retrieves the absolute path, which is the relative path from the root
     *  directory, prepended with a '/'.
     *
     * @return  null
     * @since   1.0
     */
    function getAbsolutePath ();

    /**
     * Retrieves the relative path, which is the path between a specific directory to
     *  a specific file or directory
     *
     * @return  null
     * @since   1.0
     */
    function getRelativePath ();

    /**
     * Returns a URL that can be used to identify this entry.
     *  filesystem:http://example.domain/persistent-or-temporary/path/to/file.html.
     *
     * @return  null
     * @since   1.0
     */
    function convertToUrl ();

    /**
     * Returns the value 'directory, 'file' or 'link' for the type determined
     *  from the path
     *
     * @return  null
     * @since   1.0
     */
    function getType ();

    /**
     * Returns true if the path is a directory
     *
     * @return  null
     * @since   1.0
     */
    function isDirectory ();

    /**
     * Returns true if the path is a file
     *
     * @return  null
     * @since   1.0
     */
    function isFile ();

    /**
     * Returns true if the path is a link
     *
     * @param   string
     *
     * @return  null
     * @since   1.0
     */
    function isLink ();

    /**
     * Returns the owner of the file or directory defined in the path
     *
     * @param   string
     *
     * @return  null
     * @since   1.0
     */
    function getOwner ();

    /**
     * Changes the owner to the value specified in group for the file or
     * directory defined in the path
     *
     * @param   string  $group
     *
     * @return  null
     * @since   1.0
     */
    function setOwner ($group);

    /**
     * Returns the group for the file or directory defined in the path
     *
     * @return  null
     * @since   1.0
     */
    function getGroup ();

    /**
     * Changes the group to the value specified for the file or
     * directory defined in the path
     *
     * @param   string  $group
     *
     * @return  null
     * @since   1.0
     */
    function setGroup ($group);

    /**
     * Returns associative array: 'read', 'update', 'execute'
     *  as true or false for the set group: 'owner', 'group', or 'world'
     *  and the set value for path
     *
     * @return  null
     * @since   1.0
     */
    function getPermissions ();

    /**
     * Set the values in the associative array $this->permissions
     *  where each group will have a set of three actions: 'read', 'update',
     *  'execute', each of which will have true or false.
     *
     * @return  null
     * @since   1.0
     */
    function setPermissions ();

    /**
     * Tests if the group specified: 'owner', 'group', or 'world'
     * has read access
     *
     * @param   null    $group
     *
     * @return  null
     * @since   1.0
     */
    function isReadable ($group = null);

    /**
     * Tests if the group specified: 'owner', 'group', or 'world'
     *  has write access
     *
     * @param   null    $group
     *
     * @return  null
     * @since   1.0
     */
    function isWriteable ($group = null);

    /**
     * Tests if the group specified: 'owner', 'group', or 'world'
     *  has execute access. Returns true or false
     *
     * @param   null    $group
     *
     * @return  null
     * @since   1.0
     */
    function isExecutable ($group = null);

    /**
     * Set read access to true or false for the group specified:
     *  'owner', 'group', or 'world'
     *
     * @param   null    $group
     *
     * @return  null
     * @since   1.0
     */
    function setReadable ($group = null);

    /**
     * Set write access to true or false for the group specified:
     *  'owner', 'group', or 'world'
     *
     * @param   null    $group
     *
     * @return  null
     * @since   1.0
     */
    function setWriteable ($group = null);

    /**
     * Set execute access to true or false for the group specified:
     *  'owner', 'group', or 'world'
     *
     * @param   null    $group
     *
     * @return  null
     * @since   1.0
     */
    function setExecutable ($group = null);

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
