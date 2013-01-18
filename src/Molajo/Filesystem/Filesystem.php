<?php
/**
 * Filesystem Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem;

defined ('MOLAJO') or die;

/**
 * Describes a filesystem instance
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 *
 * Full interface specification:
 *  See https://github.com/Molajo/Filesystem/doc/speifications.md
 */
interface Filesystem
{
    /**
     * Retrieves the adapter that has been injected into the Filesystem
     *
     * @return  null
     * @since   1.0
     */
    function getAdapter ();

    /**
     * Sets the current filesystem adapter
     *
     * @param   string  $adapter
     *
     * @return  null
     * @since   1.0
     */
    function setAdapter ($adapter);

    /**
     * Retrieves the name of the filesystem
     *
     * @param   string  $path
     *
     * @return  null
     * @since   1.0
     */
    function getName ();

    /**
     * Sets the name of the filesystem
     *
     * @param   string  $adapter
     *
     * @return  null
     * @since   1.0
     */
    function setName ($name);
}
