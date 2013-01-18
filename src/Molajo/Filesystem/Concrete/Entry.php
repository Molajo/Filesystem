<?php
/**
 * Entry of Filesystem
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem\Concrete;

use Molajo\Filesystem\Entry as EntryInterface;

defined ('MOLAJO') or die;

/**
 * Filesystem Entry
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 *
 * Full interface specification:
 *  See https://github.com/Molajo/Filesystem/doc/speifications.md
 */
abstract class Entry implements EntryInterface
{
    /**
     * Filesystem
     *
     * @var    object Filesystem
     * @since  1.0
     */
    protected $filesystem;

    /**
     * Adapter
     *
     * @var    object Filesystem
     * @since  1.0
     */
    protected $adapter;

    /**
     * Path (current path)
     *
     * @var    string
     * @since  1.0
     */
    protected $path;

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
    protected $permissions;

    /**
     * Constant Temporary Filesystem Storage
     *
     * @var    string
     * @since  1.0
     */
    const TEMPORARY = 0;

    /**
     * Constant Permanent Filesystem Storage
     *
     * @var    string
     * @since  1.0
     */
    const PERSISTENT = 1;

    /**
     * Construct
     *
     * @return  void
     * @since   1.0
     */
    public function __construct ($path = '', $options = array())
    {
        $this->options = $options;
        $this->path    = $path;

        $this->normalise ();
        $this->isAbsolute ();

        echo realpath ($path);
        die;

        return;
    }


    /**
     * Normalizes the given path
     *
     * @return void
     * @since  1.0
     */
    public function normalise ()
    {
        $path = $this->path;
        $path = str_replace ('\\', '/', $path);

        $prefix = $this->getAbsolutePrefix ($path);

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
                default:
                    $tokens[] = $part;
            }
        }

        $this->path = $prefix . implode ('/', $tokens);

        return;
    }

    /**
     * Indicates whether the given path is absolute or not
     *
     * @param string $path A normalized path
     *
     * @return boolean
     */
    public function isAbsolute ()
    {
        return $this->getAbsolutePrefix ();

    }

    /**
     * Returns the absolute prefix of the given path
     *
     * @param string $path A normalized path
     *
     * @return string
     */
    public function getAbsolutePrefix ()
    {
        preg_match ('|^(?P<prefix>([a-zA-Z]:)?/)|', $this->path, $matches);

        if (empty($matches['prefix'])) {
            return '';
        }

        $this->absolute_path = strtolower ($matches['prefix']);

        return $this->absolute_path;
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

    }

    /**
     * Returns a URL that can be used to identify this entry.
     *  filesystem:http://example.domain/persistent-or-temporary/path/to/file.html.
     *
     * @return  null
     * @since   1.0
     */
    public function toUrl ()
    {

    }

    /**
     * Determine if the file or directory specified in path exists
     *
     * @return  null
     * @since   1.0
     */
    public function exists ()
    {
        if (file_exists ($this->path)) {
            return true;
        }

        return false;
    }

    /**
     * Returns the value 'directory, 'file' or 'link' for the type determined from the path
     *
     * @return  null
     * @since   1.0
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
     * The name of the entry, excluding the path leading to it.
     *   Either a filename or a directory name
     *
     * @param   string
     *
     * @return  null
     * @since   1.0
     */
    public function getName ()
    {

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
     * @return  null
     * @since   1.0
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
     * @param   string  $path
     * @param   array   $permission
     * @param   string  $group
     *
     * @return  null
     * @since   1.0
     */
    public function setPermissions ()
    {
        $this->permissions = array();
        $this->group       = $this->path;
    }


    /**
     * Tests if the group specified: 'owner', 'group', or 'world' has read access
     *  Returns true or false
     *
     * @param           string
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
     * @param           string
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
     * @param           string
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
     * @param           string
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
     * @param           string
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
     * @param           string
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
     * @param   string
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
     * @param   string
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
     * @param   string
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
     * @param   string
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
     * @param           string
     * @param   string  $value
     *
     * @return  null
     * @since   1.0
     */
    public function setUpdateDate ($value)
    {

    }
}
