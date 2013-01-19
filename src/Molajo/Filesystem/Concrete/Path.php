<?php
/**
 * Path for Adapter of Filesystem
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem\Concrete;

use Molajo\Filesystem\FilesystemException;
use Molajo\Filesystem\Adapter\Adapter;
use Molajo\Filesystem\Path as PathInterface;

defined ('MOLAJO') or die;

/**
 * Path for Filesystem
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class Path extends Adapter implements PathInterface
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
     * @var    string
     * @since  1.0
     */
    protected $absolute_path;

    /**
     * Relative path for current path
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
        $this->setAdapter($adapter);
        $this->path = $this->normalise($path);
        $this->normalise ();
        $this->options = $options;
        $this->isAbsolute ();
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
        return $this->path = $this->normalise($path);
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
        if ($this->absolute_path == $this->path) {
            return true;
        }

        return false;
    }

    /**
     * Indicates whether the given path is absolute or not
     *
     * @return  boolean
     */
    public function isAbsolute ()
    {
        $this->path = $this->normalise($this->path);

        if ($this->absolute_path == $this->path) {
            return true;
        }

        return false;
    }

    /**
     * Retrieves the absolute path, which is the relative path from the root directory,
     *  prepended with a '/'.
     *
     * @return  null
     * @since   1.0
     */
    public function getAbsolutePath ()
    {
        $this->absolute_path = realpath ($this->path);

        return $this->absolute_path;
    }

    /**
     * Retrieves the relative path, which is the path between a specific directory to
     *  a specific file or directory
     *
     * @return  null
     * @since   1.0
     */
    public function getRelativePath ()
    {
        if ($this->isAbsolute () === true) {
        } else {

        }
//??????
        $this->relative_path = $this->path;

        return $this->relative_path;
    }

    /**
     * Returns a URL that can be used to identify this entry.
     *  filesystem:http://example.domain/persistent-or-temporary/path/to/file.html.
     *
     * @return  null
     * @since   1.0
     */
    public function convertToUrl ()
    {
//??????
    }

    /**
     * Returns the value 'directory, 'file' or 'link' for the type determined from the path
     *
     * @return  null
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
     * @return  null
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
     * @return  null
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
     * @return  null
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
     * @return  null
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
     * @return  null
     * @since   1.0
     */

    /**
     * @param string $owner
     *
     * @return null|void
     */
    public function setOwner ($owner)
    {
        $this->owner = $owner;
    }

    /**
     * Returns the group for the file or directory defined in the path
     *
     * @return  null
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
     * Retrieves Create Date for directory or file identified in the path
     *
     * @return  null
     * @since   1.0
     */
    public function getCreateDate ()
    {

    }

    /**
     * Retrieves Last Access Date for directory or file identified in the path
     *
     * @return  null
     * @since   1.0
     */
    public function getAccessDate ()
    {

    }

    /**
     * Retrieves Last Update Date for directory or file identified in the path
     *
     * @return  null
     * @since   1.0
     */
    public function getUpdateDate ()
    {

    }

    /**
     * Sets the Last Access Date for directory or file identified in the path
     *
     * @return  null
     * @since   1.0
     */
    public function setAccessDate ()
    {

    }

    /**
     * Sets the Last Update Date for directory or file identified in the path
     *
     * @param   string  $value
     *
     * @return  null
     * @since   1.0
     */
    public function setUpdateDate ($value)
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
        $path = str_replace ('\\', '/', $path);

        $prefix = $this->getAbsolutePath ();

        $path = substr ($path, strlen ($prefix));

        $parts = array_filter (explode ('/', $path), 'strlen');

        $tokens = array();

        foreach ($parts as $part) {
            switch ($part) {

                case '.':
                    continue;

                case '..':
                    if (0 !== count ($tokens)) {
                        array_pop ($tokens);
                        continue;

                    } elseif (! empty($prefix)) {
                        continue;
                    }
                    break;

                default:
                    $tokens[] = $part;
            }

        }

        $path = $prefix . implode ('/', $tokens);

        return $path;
    }
}
