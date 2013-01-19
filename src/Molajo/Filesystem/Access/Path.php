<?php
/**
 * Path for Adapter of Filesystem
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem\Access;

use Molajo\Filesystem\FilesystemException;
use Molajo\Filesystem\Adapter\Adapter;
use Molajo\Filesystem\Path as PathInterface;

defined ('MOLAJO') or die;

/**
 * Path for Filesystem
 *
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class Path implements PathInterface
{
    /**
     * Adapter Instance
     *
     * @var    Adapter
     * @since  1.0
     */
    protected $adapter;

    /**
     * Path
     *
     * @var    string
     * @since  1.0
     */
    protected $path;

    /**
     * Options
     *
     * @var    $options
     * @since  1.0
     */
    protected $options = array();

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
     * Relative path for current path
     *
     * A relative path describes how to get from a particular directory to a file or directory.
     *
     * @var    string
     * @since  1.0
     */
    protected $relative_path;

    /**
     * URL for current path
     *
     * @var    string
     * @since  1.0
     */
    protected $url;

    /**
     * Owner for current path
     *
     * @var    string
     * @since  1.0
     */
    protected $owner;

    /**
     * Group for current path
     *
     * @var    string
     * @since  1.0
     */
    protected $group;

    /**
     * Permissions for current path
     *
     * @var    string
     * @since  1.0
     */
    protected $permissions = array();

    /**
     * persistence of 0 (Temporary) or 1 (Permanent)
     *
     * @var    string
     * @since  1.0
     */
    protected $persistence = array();

    /**
     * Construct
     *
     * @param  Adapter  $adapter
     * @param  string   $path
     * @param  array    $options
     *
     * @since   1.0
     */
    public function __construct (Adapter $adapter, $path, $options = array())
    {
        $this->setAdapter ($adapter);
        $this->setPath ($path);
        $this->setOptions ($options);

        $this->convertToUrl ();

        if (isset($this->options['persistence'])) {
            $this->persistence = $this->options['persistence'];
        }

        return;
    }

    /**
     * Set the Adapter
     *
     * @param   Adapter  $adapter
     *
     * @return  Adapter
     * @since   1.0
     */
    public function setAdapter (Adapter $adapter)
    {
        return $this->adapter = $adapter;
    }

    /**
     * Get the Adapter
     *
     * @return  Adapter
     * @since   1.0
     */
    public function getAdapter ()
    {
        return $this->adapter;
    }

    /**
     * Set the Path
     *
     * @param   string  $path
     *
     * @return  string
     * @since   1.0
     */
    public function setPath ($path)
    {
        $this->path = $this->normalise ($path);
        return $this->getAbsolutePath ();
    }

    /**
     * Get the Path
     *
     * @return  string
     * @since   1.0
     */
    public function getPath ()
    {
        return $this->path;
    }

    /**
     * Set the Options
     *
     * @param   array  $options
     *
     * @return  array
     * @since   1.0
     */
    public function setOptions ($options = array())
    {
        if (is_array ($options)) {
            return $this->options = $options;
        }

        return $this->options = array();
    }

    /**
     * Get the Options
     *
     * @return  array  $options
     * @since   1.0
     */
    public function getOptions ()
    {
        return $this->options;
    }

    /**
     * Checks to see if the path exists
     *
     * @return  boolean
     */
    public function exists ()
    {
        return file_exists($this->path);
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
        if (substr ($this->path, 0, 1) == '/') {
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
     * Returns the value 'directory, 'file' or 'link' for the type determined from the path
     *
     * @return  string
     * @since   1.0
     * @throws  \Molajo\Filesystem\FilesystemException
     */
    public function getType ()
    {
        if ($this->isDirectory ($this->path)) {
            return 'directory';
        }

        if ($this->isFile ($this->path)) {
            return 'file';
        }

        if ($this->isLink ($this->path)) {
            return 'link';
        }

        throw new FilesystemException
        ('Not a directory, file or a link.');
    }

    /**
     * Returns true or false indicator as to whether or not the path is a directory
     *
     * @return  bool
     * @since   1.0
     */
    public function isDirectory ()
    {
        if (is_file ($this->path)) {
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
        if (is_file ($this->path)) {
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
        if (is_link ($this->path)) {
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
        //$this->group       = $this->path;
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
            $path     = substr ($path, 1, strlen ($path));
        }

        /** Unescape slashes */
        $path = str_replace ('\\', '/', $path);

        /**  Filter: empty value @link http://tinyurl.com/arrayFilterStrlen */
        $nodes = array_filter (explode ('/', $path), 'strlen');

        $normalised = array();

        foreach ($nodes as $node) {

            /** '.' means current - ignore it      */
            if ($node == '.') {

            /** '..' is parent - remove the parent */
            } elseif ($node == '..') {

                if (count($normalised) > 0) {
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
     * Retrieves the absolute path, which is the relative path from the root directory,
     *  prepended with a '/'.
     *
     * @return  string
     * @since   1.0
     */
    public function getAbsolutePath ()
    {
        $this->absolute_path = realpath ($this->path);

        return $this->absolute_path;
    }
}
