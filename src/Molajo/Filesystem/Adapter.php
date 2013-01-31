<?php
/**
 * Adapter Class
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
    public $filesystem_object;

    /**
     * Filesystem Type Instance
     *
     * @var     string
     * @since   1.0
     */
    public $filesystem_type_object;

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

                $replace = true;

                if (isset($options['replace'])) {
                    $replace = (int)$options['replace'];
                }

                if ($replace === false) {
                } else {
                    $replace = true;
                }

                if (isset($options['target_filesystem_type_object'])) {
                    $target_filesystem_type_object = $options['target_filesystem_type_object'];
                } else {
                    $target_filesystem_type_object = $this->type;
                }

                $this->action_results = $this->$action($target_directory, $replace, $target_filesystem_type_object);

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

        $this->filesystem_type_object = $this->getType();

        $this->filesystem = $this->getFilesystem();

        $this->filesystem_type_object = $this->filesystem->connect($this->filesystem_type_object);

        return $this->filesystem_type_object;
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
        $this->path = $this->filesystem->setPath($path);

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
        $this->filesystem_type_object = $this->filesystem->getMetadata();

        return $this->filesystem_type_object;
    }

    /**
     * Returns the contents of the file identified in path
     *
     * @return  mixed|string|array
     * @since   1.0
     */
    public function read()
    {
        $contents = $this->filesystem->read();

        return $contents;
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
        $this->filesystem->write($file, $replace, $data);

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
        $this->filesystem->delete($delete_subdirectories);

        return;
    }

    /**
     * Copies the file/folder in $path to the target_directory, replacing content, if indicated
     *
     * Note: $target_filesystem_type_object used to create new filesystem instance for target
     *
     * @param   string  $target_directory
     * @param   bool    $replace                      , defaults to true
     * @param   string  $target_filesystem_type_object, defaults to current
     *
     * @return  void
     * @since   1.0
     */
    public function copy($target_directory, $replace = true, $target_filesystem_type_object = '')
    {
        $this->filesystem->copy($target_directory, $replace, $target_filesystem_type_object);

        return;
    }

    /**
     * Moves the file/folder in $path to the target_directory, replacing content, if indicated
     *
     * Note: $target_filesystem_type_object used to create new filesystem instance for target
     *
     * @param   string  $target_directory
     * @param   bool    $replace                      , defaults to true
     * @param   string  $target_filesystem_type_object, defaults to current
     *
     * @return  void
     * @since   1.0
     */
    public function move($target_directory, $replace = true, $target_filesystem_type_object = '')
    {
        $this->filesystem->move($target_directory, $replace, $target_filesystem_type_object);

        return;
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
        $content = $this->filesystem->getList($recursive);

        return $content;
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
        $this->filesystem->chmod($mode);

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
        $this->filesystem->touch($time, $atime);

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
