<?php
/**
 * Local Adapter for Filesystem
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem\Adapter;

defined ('MOLAJO') or die;

use Molajo\Filesystem\FilesystemAdapter;

/**
 * Local Adapter for Filesystem
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class Local extends FilesystemAdapter
{
    /**
     * Constructor
     *
     * @param  string  $path
     * @param  array   $options
     *
     * @since  1.0
     */
    public function __construct ($path, $options = array())
    {
        parent::__construct ($path, $options);

        return;
    }

    /**
     * Does the path exist (either as a file or a folder)?
     *
     * @param string $path
     *
     * @return bool|null
     */
    public function exists ($path)
    {
        return parent::exists ($path);
    }

    /**
     * Returns the value 'directory', 'file' or 'link' for the type determined
     *  from the path
     *
     * @param   string  $path
     *
     * @return  null
     * @since   1.0
     */
    function getType ($path)
    {
        return parent::getType ($path);
    }

    /**
     * Returns true or false indicator as to whether or not the path is a directory
     *
     * @param   string  $path
     *
     * @return  bool
     * @since   1.0
     */
    public function isDirectory ($path)
    {
        return parent::isDirectory ($path);
    }

    /**
     * Returns true or false indicator as to whether or not the path is a file
     *
     * @param   string  $path
     *
     * @return  bool
     * @since   1.0
     */
    public function isFile ($path)
    {
        return parent::isFile ($path);
    }

    /**
     * Returns true or false indicator as to whether or not the path is a link
     *
     * @param   string  $path
     *
     * @return  bool
     * @since   1.0
     */
    public function isLink ($path)
    {
        return parent::isLink ($path);
    }


    /**
     * Returns the contents of the file located at path directory
     *
     * @param   string  $path
     *
     * @return  mixed
     * @since   1.0
     */
    public function read ($path = '')
    {
        if ($path == '') {
            $path = $this->path;
        }

        return parent::read ($path);
    }

    /**
     * Creates or replaces the file identified in path using the data value
     *
     * @param   string  $path
     * @param   string  $file
     * @param   string  $data
     * @param   bool    $replace
     *
     * @return  null
     * @since   1.0
     */
    function write ($path, $file, $data, $replace)
    {
        if ($path == '') {
            $path = $this->path;
        }

        return parent::write ($path, $file, $data, $replace);
    }

    /**
     * Deletes the file identified in path. Empty directories are removed if so indicated
     *
     * @param   string  $path
     * @param   bool    $delete_empty_directory
     *
     * @return  null
     * @since   1.0
     */
    public function delete ($path, $delete_empty_directory = true)
    {
        if ($path == '') {
            $path = $this->path;
        }

        return parent::delete ($path, $delete_empty_directory);
    }

    /**
     * Copies the file identified in $path to the target_adapter in the new_parent_directory,
     *  replacing existing contents, if indicated, and creating directories needed, if indicated
     *
     * Note: $target_filesystem is an instance of the Filesystem exclusive to the target portion of the copy
     *
     * @param   string  $path
     * @param   string  $target_filesystem
     * @param   string  $target_directory
     * @param   bool    $replace
     *
     * @return  null
     * @since   1.0
     */
    public function copy ($path, $target_filesystem, $target_directory, $replace = false)
    {
        if ($path == '') {
            $path = $this->path;
        }

        return parent::copy ($path, $target_filesystem, $target_directory, $replace);
    }

    /**
     * Moves the file identified in path to the location identified in the new_parent_directory
     *  replacing existing contents, if indicated, and creating directories needed, if indicated
     *
     * @param   string  $path
     * @param   string  $target_filesystem
     * @param   string  $target_directory
     * @param   bool    $replace
     *
     * @return  null
     * @since   1.0
     */
    public function move ($path, $target_filesystem, $target_directory, $replace = false)
    {
        if ($path == '') {
            $path = $this->path;
        }

        return parent::move ($path, $target_filesystem, $target_directory, $replace);
    }

    /**
     * Returns a list of files located at path directory
     *
     * @param   string  $path
     *
     * @return  mixed
     * @since   1.0
     */
    public function getList ($path)
    {
        if ($path == '') {
            $path = $this->path;
        }

        return parent::getList ($path);
    }

    /**
     * Creates directory identified in path using the data value
     *
     * @param   string  $path
     * @param   string  $new_name
     * @param   bool    $replace
     *
     * @return  null
     * @since   1.0
     */
    public function createDirectory ($path, $new_name, $replace = false)
    {
        if ($path == '') {
            $path = $this->path;
        }

        return parent::createDirectory ($path, $new_name, $replace);
    }

    /**
     * Delete directory identified in path using the data value
     *
     * @param   string  $path
     * @param   bool    $create_subdirectories
     *
     * @return  null
     * @since   1.0
     */
    public function deleteDirectory ($path, $delete_subdirectories = true)
    {
        if ($path == '') {
            $path = $this->path;
        }

        return parent::deleteDirectory ($path, $delete_subdirectories);
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
        if ($path == '') {
            $path = $this->path;
        }

        return parent::getMetadata ($path, $options);
    }

    /**
     * Normalizes the given path
     *
     * @param   $path
     *
     * @return  string
     * @since   1.0
     */
    public function normalise ($path = '')
    {
        if ($path == '') {
            $path = $this->path;
        }

        return parent::normalise ($path);
    }

    /**
     * Get the file size of a given file.
     *
     * @param   string $path
     *
     * @return  int
     * @since   1.0
     */
    public function size ($path = '')
    {
        if ($path == '') {
            $path = $this->path;
        }

        return parent::size ($path);
    }

    /**
     * FilesystemInterface
     */

    /**
     * Method to connect to a Local server
     *
     * @return  object|resource
     * @since   1.0
     */
    public function connect ()
    {
        return;
    }

    /**
     * Get the Connection
     *
     * @return  null
     * @since   1.0
     */
    function getConnection ()
    {
        return;
    }

    /**
     * Set the Connection
     *
     * @return  null
     * @since   1.0
     */
    function setConnection ()
    {
        return;
    }

    /**
     * Close the Local Connection
     *
     * @return  void
     * @since   1.0
     */
    public function close ()
    {
        return;
    }

    /**
     * Returns the owner of the file or directory defined in the path
     *
     * @return  bool
     * @since   1.0
     */
    public function getOwner ($path)
    {
        if ($path == '') {
            $path = $this->path;
        }

        return parent::getOwner ($path);
    }

    /**
     * Change the owner to the value specified for the file or directory defined in the path
     *
     * @param   string $path
     * @param   string $owner
     *
     * @return  string
     * @since   1.0
     */
    public function setOwner ($path, $owner)
    {
        if ($path == '') {
            $path = $this->path;
        }

        return parent::setOwner ($path, $owner);
    }

    /**
     * Returns the group of the file or directory defined in the path
     *
     * @return  bool
     * @since   1.0
     */
    public function getGroup ($path)
    {
        if ($path == '') {
            $path = $this->path;
        }

        return parent::getGroup ($path);
    }

    /**
     * Change the group to the value specified for the file or directory defined in the path
     *
     * @param   string $path
     * @param   string $group
     *
     * @return  string
     * @since   1.0
     */
    public function setGroup ($path, $group)
    {
        if ($path == '') {
            $path = $this->path;
        }

        return parent::setGroup ($path, $group);
    }

    /**
     * Retrieves Create Date for directory or file identified in the path
     *
     * @return  null
     * @since   1.0
     */
    public function getCreateDate ($path)
    {
        if ($path == '') {
            $path = $this->path;
        }

        return parent::getCreateDate ($path);
    }

    /**
     * Retrieves Last Access Date for directory or file identified in the path
     *
     * @return  null
     * @since   1.0
     */
    public function getAccessDate ($path)
    {
        if ($path == '') {
            $path = $this->path;
        }

        return parent::getAccessDate ($path);
    }

    /**
     * Retrieves Last Update Date for directory or file identified in the path
     *
     * @param   null    $path
     *
     * @return  null
     * @since   1.0
     */
    public function getUpdateDate ($path)
    {
        if ($path == '') {
            $path = $this->path;
        }

        return parent::getUpdateDate ($path);
    }

    /**
     * Tests for read access, returning true or false
     *
     * @param   null    $path
     *
     * @return  null
     * @since   1.0
     */
    public function isReadable ($path)
    {
        if ($path == '') {
            $path = $this->path;
        }

        return parent::isReadable ($path);
    }

    /**
     * Tests for write access, returning true or false
     *
     * @param   null    $path
     *
     * @return  null
     * @since   1.0
     */
    public function isWriteable ($path)
    {
        if ($path == '') {
            $path = $this->path;
        }

        return parent::isWriteable ($path);
    }

    /**
     * Tests for execute access, returning true or false
     *
     * @param   null    $path
     *
     * @return  null
     * @since   1.0
     */
    public function isExecutable ($path)
    {
        if ($path == '') {
            $path = $this->path;
        }

        return parent::isExecutable ($path);
    }

    /**
     * Set read access to true or false for the group specified: 'owner', 'group', or 'world'
     *
     * @param   null    $group
     *
     * @return  null
     * @since   1.0
     */
    public function setReadable ($path, $group = null, $permission = true)
    {
        if ($path == '') {
            $path = $this->path;
        }

        return parent::setReadable ($path, $group, $permission);
    }

    /**
     * Set write access to true or false for the group specified: 'owner', 'group', or 'world'
     *
     * @param   null    $group
     *
     * @return  null
     * @since   1.0
     */
    public function setWriteable ($path, $group = null, $permission = true)
    {
        if ($path == '') {
            $path = $this->path;
        }

        return parent::setWriteable ($path, $group, $permission);
    }

    /**
     * Set execute access to true or false for the group specified: 'owner', 'group', or 'world'
     *
     * @param   null    $group
     *
     * @return  null
     * @since   1.0
     */
    public function setExecutable ($path, $group = null, $permission = true)
    {
        if ($path == '') {
            $path = $this->path;
        }

        return parent::setExecutable ($path, $group, $permission);
    }

    /**
     * Sets the Last Access Date for directory or file identified in the path
     *
     * @param   string  $path
     * @param   string  $value
     *
     * @return  null
     * @since   1.0
     */
    public function setAccessDate ($path, $value)
    {
        if ($path == '') {
            $path = $this->path;
        }

        return parent::setAccessDate ($path, $value);
    }

    /**
     * Sets the Last Update Date for directory or file identified in the path
     *
     * @param   string  $path
     * @param   string  $value
     *
     * @return  null
     * @since   1.0
     */
    public function setUpdateDate ($path, $value)
    {
        if ($path == '') {
            $path = $this->path;
        }

        return parent::setUpdateDate ($path, $value);
    }
}
