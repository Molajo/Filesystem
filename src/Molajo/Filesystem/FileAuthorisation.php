<?php
/**
 * FileAuthorisation Driver
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem;

defined ('MOLAJO') or die;

use \RuntimeException;
use Molajo\Filesystem\Exception\FileException;

/**
 * FileAuthorisation Driver
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
Class FileAuthorisation implements FileAuthorisationInterface
{
    /**
     * Options
     *
     * @var    $options
     * @since  1.0
     */
    protected $options = array();

    /**
     * Path
     *
     * @var    string
     * @since  1.0
     */
    protected $path;

    /**
     * Absolute path for current path
     *
     * An absolute path is a relative path from the root directory, prepended with a '/'.
     *
     * @var    string
     * @since  1.0
     */
    protected $absolute_path;

    /**
     * Construct
     *
     * @param   array  $options
     *
     * @since   1.0
     */
    public function __construct ($options)
    {
        $this->options = $options;

        return;
    }

    /**
     * Checks to see if the path exists
     *
     * @return  boolean
     */
    public function exists ($path)
    {
        return file_exists ($path);
    }

    /**
     * Normalizes the given path
     *
     * @param   $path
     *
     * @return  string
     * @since   1.0
     */
    public function normalise ($path)
    {
        $absolute_path = false;
        if (substr ($path, 0, 1) == '/') {
            $absolute_path = true;
            $path          = substr ($path, 1, strlen ($path));
        }

        /** Unescape slashes */
        $path = str_replace ('\\', '/', $path);

        /**  Filter: empty value
         * @link http://tinyurl.com/arrayFilterStrlen
         */
        $nodes = array_filter (explode ('/', $path), 'strlen');

        $normalised = array();

        foreach ($nodes as $node) {

            /** '.' means current - ignore it      */
            if ($node == '.') {

                /** '..' is parent - remove the parent */
            } elseif ($node == '..') {

                if (count ($normalised) > 0) {
                    array_pop ($normalised);
                }

            } else {
                $normalised[] = $node;
            }

        }

        $path = implode ('/', $normalised);
        if ($absolute_path === true) {
            $path = '/' . $path;
        }

        return $path;
    }


    /**
     * Indicates whether the given path is absolute or not
     *
     * Relative path - describes how to get from a particular directory to a file or directory
     * Absolute Path - relative path from the root directory, prepended with a '/'.
     *
     * @return  boolean
     * @since   1.0
     */
    public function isAbsolute ()
    {
        if (substr ($path, 0, 1) == '/') {
            return true;
        }

        return false;
    }

    /**
     * Returns a URL that can be used to identify this entry.
     *  filesystem:http://example.domain/persistent-or-temporary/path/to/file.html.
     *
     * @return  bool
     * @since   1.0
     */
    public function convertToUrl ()
    {
        return false;
    }


    /**
     * Returns true or false indicator as to whether or not the path is a directory
     *
     * @return  bool
     * @since   1.0
     */
    public function isDirectory ()
    {
        if (is_file ($path)) {
            return true;
        }

        return false;
    }

    /**
     * Returns true or false indicator as to whether or not the path is a file
     *
     * @return  bool
     * @since   1.0
     */
    public function isFile ()
    {
        if (is_file ($path)) {
            return true;
        }

        return false;
    }

    /**
     * Returns true or false indicator as to whether or not the path is a link
     *
     * @return  bool
     * @since   1.0
     */
    public function isLink ()
    {
        if (is_link ($path)) {
            return true;
        }

        return false;
    }

    /**
     * Returns the owner of the file or directory defined in the path
     *
     * @return  bool
     * @since   1.0
     */
    public function getOwner ()
    {
        return $this->owner;
    }

    /**
     * Changes the owner to the value specified for the file or directory defined in the path
     *
     * @param   string $owner
     *
     * @return  string
     * @since   1.0
     */
    public function setOwner ($owner)
    {
        $this->owner = $owner;

        return $this->owner;
    }

    /**
     * Returns the group for the file or directory defined in the path
     *
     * @return  string
     * @since   1.0
     */
    public function getGroup ()
    {
        return $this->group;
    }

    /**
     * Changes the group to the value specified for the file or directory defined in the path
     *
     * @param   string  $group
     *
     * @return  null
     * @since   1.0
     */
    public function setGroup ($group)
    {
        $this->group = $group;

        return $this->group;
    }

    /**
     * Returns associative array: 'read', 'update', 'execute' as true or false
     *  for the set group: 'owner', 'group', or 'world' and the set value for path
     *
     * @return  null
     * @since   1.0
     */
    public function getPermissions ()
    {
        //set group
        // set path
        return $this->permissions;
    }

    /**
     * Using an associative array with three entries: 'read', 'update', 'execute' with a value for
     *  each entry as either 'true' or 'false' for the group specified: 'owner', 'group',  or 'world'
     *
     * @return  null
     * @since   1.0
     */
    public function setPermissions ()
    {
        //$this->permissions = array();
        //$this->group       = $path;
    }


    /**
     * Tests if the group specified: 'owner', 'group', or 'world' has read access
     *  Returns true or false
     *
     * @param   null    $group
     *
     * @return  null
     * @since   1.0
     */
    public function isReadable ($group = null)
    {

    }

    /**
     * Tests if the group specified: 'owner', 'group', or 'world' has write access
     *  Returns true or false
     *
     * @param   null    $group
     *
     * @return  null
     * @since   1.0
     */
    public function isWriteable ($group = null)
    {

    }

    /**
     * Tests if the group specified: 'owner', 'group', or 'world' has execute access
     *  Returns true or false
     *
     * @param   null    $group
     *
     * @return  null
     * @since   1.0
     */
    public function isExecutable ($group = null)
    {

    }

    /**
     * Set read access to true or false for the group specified: 'owner', 'group', or 'world'
     *
     * @param   null    $group
     *
     * @return  null
     * @since   1.0
     */
    public function setReadable ($group = null)
    {

    }

    /**
     * Set write access to true or false for the group specified: 'owner', 'group', or 'world'
     *
     * @param   null    $group
     *
     * @return  null
     * @since   1.0
     */
    public function setWriteable ($group = null)
    {

    }

    /**
     * Set execute access to true or false for the group specified: 'owner', 'group', or 'world'
     *
     * @param   null    $group
     *
     * @return  null
     * @since   1.0
     */
    public function setExecutable ($group = null)
    {

    }


    /**
     * Retrieves the absolute path, which is the relative path from the root directory,
     *  prepended with a '/'.
     *
     * @return  string
     * @since   1.0
     */
    public function getAbsolutePath ()
    {
        $this->absolute_path = realpath ($path);

        return $this->absolute_path;
    }


    /**
     * Retrieves metadata for the file specified in path and returns an associative array
     *  minimally populated with: last_accessed_date, last_updated_date, size, mimetype,
     *  absolute_path, relative_path, filename, and file_extension.
     *
     * @param   string  $path
     * @param   string  $options
     *
     * @return  null
     * @since   1.0
     */
    public function getMetadata ($path, $options)
    {

    }


    /**
     * Retrieves metadata for the file specified in path and returns an associative array
     *  minimally populated with: last_accessed_date, last_updated_date, size, mimetype,
     *  absolute_path, relative_path, filename, and file_extension.
     *
     * @param   string  $path
     * @param   string  $options
     *
     * @return  null
     * @since   1.0
     */
    public function setMetadata ($path, $options)
    {

    }


}
