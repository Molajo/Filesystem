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
use Molajo\Filesystem\Targetinterface\FilesystemInterface;
use Molajo\Filesystem\Exception\FileException;

/**
 * Adapter Class
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
Class Adapter implements FilesystemInterface
{
    /**
     * Filesystem Type Name
     *
     * @var    string
     * @since  1.0
     */
    protected $type;

    /**
     * Filesystem Type Instance
     *
     * @var     string
     * @since   1.0
     */
    protected $filesystem_type;

    /**
     * Filesystem Object and Interface
     *
     * @var     object  Filesystem
     * @since   1.0
     */
    public $filesystem;

    /**
     * Construct
     *
     * @param   string  $type
     * @param   string  $action
     * @param   string  $path
     * @param   array   $options
     *
     * @since   1.0
     * @throws  FileException
     */
    public function __construct($type = 'Local', $path = '', $action = '', $options = array())
    {
        $this->connect($type);

        if ($path == '') {
            return $this->filesystem_type;
        }

        $this->path = $this->setPath($path);

        $this->getMetadata();

        if ($action == '') {
            return $this->filesystem_type;
        }

        switch ($action) {
            case 'read':
                return $this->read();
                break;

            case 'write':
                return $this->write($file, $replace, $data);
                break;

            case 'delete':
                return $this->delete($delete_subdirectories);
                break;

            case 'copy':
                return $this->copy($target_directory, $replace, $target_filesystem_type);
                break;

            case 'move':
                return $this->move($target_directory, $replace, $target_filesystem_type);
                break;

            case 'getList':
                return $this->getList($recursive);
                break;

            case 'chmod':
                return $this->chmod($mode);
                break;

            case 'touch':
                return $this->touch($time, $atime);
                break;
        }


        return $this->filesystem_type;
    }


    /**
     * Connect to the Filesystem
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

        $this->filesystem_type = $this->getType();

        $this->filesystem      = $this->getFilesystem();
        $this->filesystem = $this->filesystem->connect();

        return $this->filesystem;
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

        return new $class();
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

        return new Filesystem($this->filesystem_type);
    }

    /**
     * Set the Path
     *
     * @return  object  Filesystem
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
        $this->filesystem = $this->filesystem->getMetadata();

        return $this->filesystem;
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
     * @param   string  $file
     * @param   bool    $replace
     * @param   string  $data
     *
     * @return  null
     * @since   1.0
     */
    public function write($file, $replace = true, $data = '')
    {
        $this->filesystem->write($file, $replace, $data);

        return;
    }

    /**
     * Deletes the file or folder identified in path. Deletes subdirectories, if so indicated
     *
     * @param   string  $path
     * @param   bool    $delete_subdirectories, default true (for directories)
     *
     * @return  void
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
     * Note: $target_filesystem_type used to create new filesystem instance for target
     *
     * @param   string  $target_directory
     * @param   bool    $replace               , defaults to true
     * @param   string  $target_filesystem_type, defaults to current
     *
     * @return  void
     * @since   1.0
     */
    public function copy($target_directory, $replace = true, $target_filesystem_type = '')
    {
        $this->filesystem->copy($target_directory, $replace, $target_filesystem_type);

        return;
    }

    /**
     * Moves the file/folder in $path to the target_directory, replacing content, if indicated
     *
     * Note: $target_filesystem_type used to create new filesystem instance for target
     *
     * @param   string  $target_directory
     * @param   bool    $replace               , defaults to true
     * @param   string  $target_filesystem_type, defaults to current
     *
     * @return  void
     * @since   1.0
     */
    public function move($target_directory, $replace = true, $target_filesystem_type = '')
    {
        $this->filesystem->move($target_directory, $replace, $target_filesystem_type);

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
    public function getList($recursive = '')
    {
        $content = $this->filesystem->getList($recursive);

        return $content;
    }

    /**
     * Change file mode
     *
     * @param   int     $mode
     *
     * @return  void
     * @since   1.0
     */
    public function chmod($mode)
    {
        $this->filesystem->getList($mode);

        return;
    }

    /**
     * Update the touch time and/or the access time for the directory or file identified in the path
     *
     * @param   int     $time
     * @param   int     $atime
     *
     * @return  void
     * @since   1.0
     */
    public function touch($time, $atime = null)
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
