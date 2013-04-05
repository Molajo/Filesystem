<?php
/**
 * Adapter for Filesystem
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Filesystem;

defined('MOLAJO') or die;

use Molajo\Filesystem\Adapter\AdapterInterface;
use Molajo\Filesystem\Adapter\ActionsInterface;
use Exception;
use Molajo\Filesystem\Exception\FilesystemException;

/**
 * Adapter for Filesystem
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @since     1.0
 */
class Adapter implements AdapterInterface, ActionsInterface
{
    /**
     * Filesystem Type
     *
     * @var     object
     * @since   1.0
     */
    public $fs;

    /**
     * Constructor
     *
     * @param   string $filesystem_type
     * @param   array  $options
     *
     * @return  mixed
     * @since   1.0
     */
    public function __construct($filesystem_type = 'Local', $options = array())
    {
        if ($filesystem_type == '') {
            $filesystem_type = 'Local';
        }

        $this->getFilesystemType($filesystem_type);

        $this->connect($options);
    }

    /**
     * Get the Filesystem Type (ex., Local, Ftp, etc.)
     *
     * @param   string $filesystem_type
     *
     * @return  $this
     * @since   1.0
     * @throws  FilesystemException
     */
    protected function getFilesystemType($filesystem_type)
    {
        $class = 'Molajo\\Filesystem\\Type\\' . $filesystem_type;

        try {

            $this->fs = new $class($filesystem_type);

        } catch (Exception $e) {

            throw new FilesystemException
            ('Filesystem: Could not instantiate Filesystem Type: ' . $fieldhandler_type
                . ' Class: ' . $class);
        }

        if (class_exists($class)) {
        } else {
            throw new FilesystemException
            ('Filesystem Type class ' . $class . ' does not exist.');
        }

        return;
    }

    /**
     * Connect to the Filesystem Type
     *
     * @param   array $options
     *
     * @return  $this
     * @since   1.0
     * @throws  FilesystemException
     */
    public function connect($options = array())
    {
        try {
            $this->fs->connect($options);

        } catch (Exception $e) {

            throw new FilesystemException
            ('Filesystem: Caught Exception: ' . $e->GetMessage());
        }

        return;
    }

    /**
     * Returns the contents of the file identified in path
     *
     * @param   string  $path
     *
     * return array|mixed|object|string
     * @since   1.0
     * @throws  FilesystemException
     */
    public function read($path)
    {
        if ($path == '') {
            throw new FilesystemException
            ('Filesystem Path is required, but was not provided.');
        }

        $options = array();

        $this->setPath($path);
        $this->getMetadata();
        $this->doAction('read', $options);
        $this->close();

        return $this->fs;
    }

    /**
     * Returns a list of file and folder names located at path directory
     *
     * @param   string $path
     * @param   bool   $recursive
     *
     * @return  $this
     * @since   1.0
     * @throws  FilesystemException
     */
    public function getList($path, $recursive = false)
    {
        if ($path == '') {
            throw new FilesystemException
            ('Filesystem Path is required, but was not provided.');
        }

        $options = array();
        $options['recursive'] = $recursive;

        $this->setPath($path);
        $this->getMetadata();
        $this->doAction('getList', $options);
        $this->close();

        return $this->fs;
    }

    /**
     * Creates or replaces the file or directory identified in path using the data value
     *
     * @param   string $path
     * @param   string $file
     * @param   bool   $replace
     * @param   string $data
     *
     * @return  $this
     * @since   1.0
     * @throws  FilesystemException
     */
    public function write($path, $file = '', $replace = true, $data = '')
    {
        if ($path == '') {
            throw new FilesystemException
            ('Filesystem Path is required, but was not provided.');
        }

        $options = array();
        $options['file'] = $file;
        $options['replace'] = $replace;
        $options['data'] = $data;

        $this->setPath($path);
        $this->getMetadata();
        $this->doAction('write', $options);
        $this->close();

        return $this->fs;
    }

    /**
     * Deletes the file or folder identified in path. Deletes subdirectories, if so indicated
     *
     * @param   string $path
     * @param   bool   $delete_subdirectories
     *
     * @return  $this
     * @since   1.0
     * @throws  FilesystemException
     */
    public function delete($path, $delete_subdirectories = true)
    {
        if ($path == '') {
            throw new FilesystemException
            ('Filesystem Path is required, but was not provided.');
        }

        $options = array();
        $options['delete_subdirectories'] = $delete_subdirectories;

        $this->setPath($path);
        $this->getMetadata();
        $this->doAction('delete', $options);
        $this->close();

        return $this->fs;
    }

    /**
     * Copies the file/folder in $path to the target_directory, replacing content, if indicated
     *
     * Note: $target_filesystem_type used to create new filesystem instance for target
     *
     * @param   string $path
     * @param   string $target_directory
     * @param   string $target_name
     * @param   bool   $replace
     * @param   string $target_filesystem_type
     *
     * @return  $this
     * @since   1.0
     * @throws  FilesystemException
     */
    public function copy($path, $target_directory, $target_name = '',
        $replace = true, $target_filesystem_type = '')
    {
        if ($path == '') {
            throw new FilesystemException
            ('Filesystem Path is required, but was not provided.');
        }

        $options = array();
        $options['target_directory'] = $target_directory;
        $options['target_name'] = $target_name;
        $options['replace'] = $replace;
        $options['target_filesystem_type'] = $target_filesystem_type;

        $this->setPath($path);
        $this->getMetadata();
        $this->doAction('copy', $options);
        $this->close();

        return $this->fs;
    }

    /**
     * Moves the file/folder in $path to the target_directory, replacing content, if indicated
     *
     * Note: $target_filesystem_type used to create new filesystem instance for target
     *
     * @param   string $path
     * @param   string $target_directory
     * @param   string $target_name
     * @param   bool   $replace
     * @param   string $target_filesystem_type
     *
     * @return  $this
     * @since   1.0
     * @throws  FilesystemException
     */
    public function move($path, $target_directory, $target_name = '',
        $replace = true, $target_filesystem_type = '')
    {
        if ($path == '') {
            throw new FilesystemException
            ('Filesystem Path is required, but was not provided.');
        }

        $options = array();
        $options['target_directory'] = $target_directory;
        $options['target_name'] = $target_name;
        $options['replace'] = $replace;
        $options['target_filesystem_type'] = $target_filesystem_type;

        $this->setPath($path);
        $this->getMetadata();
        $this->doAction('move', $options);
        $this->close();

        return $this->fs;
    }

    /**
     * Change owner for file or folder identified in path
     *
     * @param   string $path
     * @param   string $user_name
     *
     * @return  $this
     * @since   1.0
     * @throws  FilesystemException
     */
    public function changeOwner($path, $user_name)
    {
        if ($path == '') {
            throw new FilesystemException
            ('Filesystem Path is required, but was not provided.');
        }

        $options = array();
        $options['user_name'] = $user_name;

        $this->setPath($path);
        $this->getMetadata();
        $this->doAction('changeOwner', $options);
        $this->close();

        return $this->fs;
    }

    /**
     * Change group for file or folder identified in path
     *
     * @param   string $path
     * @param   string $group_id
     *
     * @return  $this
     * @since   1.0
     * @throws  FilesystemException
     */
    public function changeGroup($path, $group_id)
    {
        if ($path == '') {
            throw new FilesystemException
            ('Filesystem Path is required, but was not provided.');
        }

        $options = array();
        $options['group_id'] = $group_id;

        $this->setPath($path);
        $this->getMetadata();
        $this->doAction('changeGroup', $options);
        $this->close();

        return $this->fs;
    }

    /**
     * Change permissions for file or folder identified in path
     *
     * @param   string $path
     * @param   int    $permission
     *
     * @return  $this
     * @since   1.0
     * @throws  FilesystemException
     */
    public function changePermission($path, $permission)
    {
        if ($path == '') {
            throw new FilesystemException
            ('Filesystem Path is required, but was not provided.');
        }

        $options = array();
        $options['permission'] = $permission;

        $this->setPath($path);
        $this->getMetadata();
        $this->doAction('changePermission', $options);
        $this->close();

        return $this->fs;
    }

    /**
     * Update the modification time and access time (touch) for the directory or file identified in the path
     *
     * @param   string $path
     * @param   int    $modification_time
     * @param   int    $access_time
     *
     * @return  $this
     * @since   1.0
     * @throws  FilesystemException
     */
    public function touch($path, $modification_time = null, $access_time = null)
    {
        if ($path == '') {
            throw new FilesystemException
            ('Filesystem Path is required, but was not provided.');
        }

        $options = array();
        $options['modification_time'] = $modification_time;
        $options['access_time'] = $access_time;

        $this->setPath($path);
        $this->getMetadata();
        $this->doAction('touch', $options);
        $this->close();

        return $this->fs;
    }

    /**
     * Set the Path
     *
     * @param   string $path
     *
     * @return  $this
     * @since   1.0
     * @throws  FilesystemException
     */
    public function setPath($path)
    {
        $this->fs->setPath($path);

        return $this;
    }

    /**
     * Retrieves and set metadata for the file specified in path
     *
     * @return  $this
     * @since   1.0
     * @throws  FilesystemException
     */
    public function getMetadata()
    {
        $this->fs->getMetadata();

        return $this;
    }

    /**
     * Execute the action requested
     *
     * @param   string  $action
     * @param   array  $options
     *
     * @return  $this
     * @since   1.0
     * @throws  FilesystemException
     */
    public function doAction($action = '', $options = array())
    {
        $this->fs->doAction($action);

        return $this;
    }

    /**
     * Close the Connection
     *
     * @return  $this
     * @since   1.0
     * @throws  FilesystemException
     */
    public function close()
    {
        $this->fs->close();

        return $this;
    }
}
