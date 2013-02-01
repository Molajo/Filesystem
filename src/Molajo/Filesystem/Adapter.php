<?php

//todo: figure out how to pass in the timezone

/**
 * Filesystem Adapter
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem;

defined('MOLAJO') or die;

use Molajo\Filesystem\Targetinterface\Filesystem;
use Molajo\Filesystem\Targetinterface\FileInterface;
use Molajo\Filesystem\Exception\FileException;

/**
 * Adapter Class
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
Class Adapter implements FileInterface
{
    /**
     * Filesystem Type Name
     *
     * @var    string
     * @since  1.0
     */
    protected $type;

    /**
     * Filesystem Object and Interface
     *
     * @var     object  Filesystem
     * @since   1.0
     */
    public $fs;

    /**
     * Filesystem Type Instance
     *
     * @var     string
     * @since   1.0
     */
    public $fs_type;

    /**
     * Action Results
     *
     * @var     mixed
     * @since   1.0
     */
    public $action_results;

    /**
     * Construct
     *
     * @param   string  $type
     * @param   string  $path
     * @param   string  $action
     * @param   array   $options
     *
     * @since   1.0
     */
    public function __construct($type = 'Local', $path = '', $action = '', $options = array())
    {
        if (ini_get('date.timezone') == null) {
            date_default_timezone_set('America/Chicago');
        }

        $this->connect($type);

        if ($path == '') {
            return $this;
        }

        $this->path = $this->setPath($path);

        $this->getMetadata();

        if ($action == '') {
            return $this;
        }

        $action = strtolower($action);

        switch ($action) {

            case 'read':
                $this->action_results = $this->read();

                break;

            case 'write':

                if (isset($options['file'])) {
                    $file = $options['file'];
                } else {
                    throw new \BadMethodCallException
                    ('Filesystem Adapter: Must provide filename for write request. Path: ' . $this->path);
                }

                $replace = true;
                if (isset($options['replace'])) {
                    $replace = $options['replace'];
                }
                if ($replace === false) {
                } else {
                    $replace = true;
                }

                if (isset($options['data'])) {
                    $data = $options['data'];
                } else {
                    $data = '';
                }

                $this->action_results = $this->write($file, $replace, $data);

                break;

            case 'delete':

                $delete_subdirectories = true;

                if (isset($options['delete_subdirectories'])) {
                    $delete_subdirectories = (int)$options['delete_subdirectories'];
                }

                if ($delete_subdirectories === false) {
                } else {
                    $delete_subdirectories = true;
                }

                $this->action_results = $this->delete($delete_subdirectories);

                break;

            case 'copy':
            case 'move':

                if (isset($options['target_directory'])) {
                    $target_directory = $options['target_directory'];

                } else {
                    throw new \BadMethodCallException
                    ('Filesystem Adapter: Must provide target_directory for copy request. Path: ' . $this->path);
                }

                if (isset($options['target_name'])) {
                    $target_name = $options['target_name'];

                } else {
                    $target_name = '';
                }

                $replace = true;

                if (isset($options['replace'])) {
                    $replace = (int)$options['replace'];
                }

                if ($replace === false) {
                } else {
                    $replace = true;
                }

                if (isset($options['target_filesystem_type'])) {
                    $target_filesystem_type = $options['target_filesystem_type'];
                } else {
                    $target_filesystem_type = $this->type;
                }

                $this->action_results
                    = $this->$action($target_directory, $target_name, $replace, $target_filesystem_type);

                break;

            case 'getList':

                $recursive = true;

                if (isset($options['recursive'])) {
                    $recursive = (int)$options['recursive'];
                }

                if ($recursive === false) {
                } else {
                    $recursive = true;
                }

                $this->action_results = $this->getList($recursive);

                break;

            case 'chmod':

                $mode = '';

                if (isset($options['mode'])) {
                    $mode = $options['recursive'];
                }

                $this->action_results = $this->chmod($mode);

                break;

            case 'touch':

                $time = null;

                if (isset($options['time'])) {
                    $time = (int)$options['time'];
                }

                $atime = null;

                if (isset($options['atime'])) {
                    $atime = (int)$options['atime'];
                }

                $this->action_results = $this->touch($time, $atime);

                break;
        }

        return $this;
    }

    /**
     * Connect to the Filesystem
     *
     * @param   string  Filesystem Type (ex. 'Local')
     *
     * @return  object  Filesystem
     * @since   1.0
     */
    public function connect($type)
    {
        /** Get the Filesystem Type */
        $this->type = $type;

        if ($this->type == '') {
            $this->type = 'Local';
        }

        /** Gets an instance of the File System Type, ex. Local, FTP, Cache, etc. */
        $this->fs_type = $this->getType();

        /** Gets an instance of the Filesystem Target Interface for Filesystem Adapter */
        $this->fs = $this->getFilesystem();

        /** Pass the Filesystem Type into the Filesystem Interface adapter and Connect */
        $this->fs_type = $this->fs->connect($this->fs_type);

        return $this->fs_type;
    }

    /**
     * Get the Filesystem Type (ex., Local, Ftp, Virtual, etc.)
     *
     * @return  object  Filesystem Type
     * @since   1.0
     * @throws  FileException
     */
    protected function getType()
    {
        $class = 'Molajo\\Filesystem\\Type\\' . $this->type;

        if (class_exists($class)) {
        } else {
            throw new FileException('Filesystem Type Class ' . $class . ' does not exist.');
        }

        return new $class($this->type);
    }

    /**
     * Get Filesystem Object
     *
     * @return  object  Filesystem
     * @since   1.0
     * @throws  FileException
     */
    protected function getFilesystem()
    {
        $class = 'Molajo\\Filesystem\\Targetinterface\\Filesystem';

        if (class_exists($class)) {
        } else {
            throw new FileException('Filesystem Adapter Class Filesystem does not exist.');
        }

        return new Filesystem();
    }

    /**
     * Set the Path
     *
     * @param   string  $path
     *
     * @return  string
     * @since   1.0
     */
    public function setPath($path)
    {
        $this->path = $this->fs->setPath($path);

        return $this->path;
    }

    /**
     * Get Filesystem Metadata
     *
     * @return  object  Filesystem
     * @since   1.0
     */
    public function getMetadata()
    {
        $this->fs_type = $this->fs->getMetadata();

        return $this->fs_type;
    }

    /**
     * Returns the contents of the file identified in path
     *
     * @return  mixed|string|array
     * @since   1.0
     */
    public function read()
    {
        $contents = $this->fs->read();

        return $contents;
    }

    /**
     * Returns a list of file and folder names located at path directory
     *
     * @param   bool  $recursive
     * todo: add filters, extension type, wild card, specific filenames, date ranges
     *
     * @return  array
     * @since   1.0
     */
    public function getList($recursive = false)
    {
        $content = $this->fs->getList($recursive);

        return $content;
    }

    /**
     * Creates or replaces the file or directory identified in path using the data value
     *
     * @param   string  $file       spaces for create directory
     * @param   bool    $replace
     * @param   string  $data       spaces for create directory
     *
     * @return  null
     * @since   1.0
     */
    public function write($file = '', $replace = true, $data = '')
    {
        $this->fs->write($file, $replace, $data);

        return;
    }

    /**
     * Deletes the file or folder identified in path. Deletes subdirectories, if so indicated
     *
     * @param   bool    $delete_subdirectories  defaults true (for directories)
     *
     * @return  null
     * @since   1.0
     */
    public function delete($delete_subdirectories = true)
    {
        $this->fs->delete($delete_subdirectories);

        return;
    }

    /**
     * Copies the file/folder in $path to the target_directory, replacing content, if indicated
     *
     * Note: $target_filesystem_type used to create new filesystem instance for target
     *
     * @param   string  $target_directory
     * @param   bool    $target_name
     * @param   bool    $replace                  defaults to true
     * @param   string  $target_filesystem_type   defaults to current
     *
     * @return  bool
     * @since   1.0
     */
    public function copy($target_directory, $target_name, $replace = true, $target_filesystem_type = '')
    {
        return $this->fs->copy($target_directory, $target_name, $replace, $target_filesystem_type);
    }

    /**
     * Moves the file/folder in $path to the target_directory, replacing content, if indicated
     *
     * Note: $target_filesystem_type used to create new filesystem instance for target
     *
     * @param   string  $target_directory
     * @param   bool    $target_name
     * @param   bool    $replace                  defaults to true
     * @param   string  $target_filesystem_type   defaults to current
     *
     * @return  bool
     * @since   1.0
     */
    public function move($target_directory, $target_name, $replace = true, $target_filesystem_type = '')
    {
        return $this->fs->move($target_directory, $target_name, $replace, $target_filesystem_type);
    }

    /**
     * Change file mode
     *
     * @param   string  $mode
     *
     * @return  void
     * @since   1.0
     */
    public function chmod($mode = '')
    {
        $this->fs->chmod($mode);

        return;
    }

    /**
     * Update the touch time and/or the access time for the directory or file identified in the path
     *
     * @param   null     $time
     * @param   null     $atime
     *
     * @return  void
     * @since   1.0
     */
    public function touch($time = null, $atime = null)
    {
        $this->fs->touch($time, $atime);

        return;
    }

    /**
     * Close the Connection
     *
     * @return  void
     * @since   1.0
     */
    public function close()
    {
        return;
    }
}
