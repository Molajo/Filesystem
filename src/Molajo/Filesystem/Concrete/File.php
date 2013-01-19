<?php
/**
 * File Instance for Filesystem
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem\Concrete;

defined ('MOLAJO') or die;

use Molajo\Filesystem\File as FileInterface;

use Molajo\Filesystem\Adapter;

/**
 * Describes a file instance for Filesystem
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 *
 * Full interface specification:
 *  See https://github.comsrc/Molajo/Filesystem/doc/speifications.md
 */
Class File extends Path implements FileInterface
{
    /**
     * File Name
     *
     * @var    Adapter
     * @since  1.0
     */
    protected $name;

    /**
     * Construct
     *
     * @param   Adapter     $adapter
     * @param   Filesystem  $filesystem
     * @param   array       $options
     *
     * @since   1.0
     */
    public function __construct (Filesystem $filesystem, Adapter $adapter, $path, $options = array())
    {
        if (isset($this->options['name'])) {
            $this->name = $this->options['name'];
        }

        return;
    }

    /**
     * Sets the name of the file specified in the path
     *
     * @param   string  $path
     *
     * @return  null
     * @since   1.0
     */
    public function setName ($path)
    {

    }

    /**
     * Retrieves the name of the file specified in the path
     *
     * @return  null
     * @since   1.0
     */
    public function getName ()
    {
        return $this->filename;
    }

    /**
     * Retrieves the extension type for the file identified in the path
     *
     * @return  null
     * @since   1.0
     */
    public function getExtension ()
    {

    }

    /**
     * Sets the extension type for the file identified in the path
     *
     * @param   string $path
     *
     * @return  null
     * @since   1.0
     */
    public function setExtension ($path)
    {

    }

    /**
     * Retrieves metadata for the file specified in path and returns an associative array
     *  minimally populated with: last_accessed_date, last_updated_date, size, mimetype,
     *  absolute_path, relative_path, filename, and file_extension.
     *
     * @param   string  $path
     *
     * @return  null
     * @since   1.0
     */
    public function setMetadata ($path)
    {

    }

    /**
     * Retrieves metadata for the file specified in path and returns an associative array
     *  minimally populated with: last_accessed_date, last_updated_date, size, mimetype,
     *  absolute_path, relative_path, filename, and file_extension.
     *
     * @return  null
     * @since   1.0
     */
    public function getMetadata ()
    {

    }

    /**
     * Returns the contents of the file located at path directory
     *
     * @param   string  $path
     *
     * @return  mixed;
     * @since   1.0
     */
    public function read ($path)
    {
        $this->path = $this->normalise($path);

        if (file_exists($this->path)) {
            return file_get_contents($this->path);
        }

        return false;
    }

    /**
     * Creates or replaces the file identified in path using the data value
     *
     * @param   string  $path
     * @param   string  $file
     * @param           $data
     * @param   bool    $replace
     * @param   bool    $create_directories
     *
     * @return  null
     * @since   1.0
     */
    public function write ($path, $data, $replace = false, $create_directories = true)
    {
        $this->path = $this->normalise($path);

        if ($replace === false) {
            if (file_exists($this->path)) {
                return false;
            }
        }

        if ($create_directories === false) {
            if (file_exists($this->path)) {
            } else {
                return false;
            }
        }

        if (file_exists($this->path)) {
            return file_get_contents($this->path);
        }

        \file_put_contents($this->path, $data);

        return false;
    }

    /**
     * Copies the file identified in $path to the target_adapter in the new_parent_directory,
     *  replacing existing contents, if indicated, and creating directories needed, if indicated
     *
     * Note: File $target is an instance of the Filesystem exclusive to the target portion of the copy
     *
     * @param   string  $path
     * @param   File    $target
     * @param   string  $target_directory
     * @param   bool    $replace
     * @param   bool    $create_directories
     *
     * @return  null
     * @since   1.0
     */
    public function copy ($path, File $target, $target_directory, $replace = false, $create_directories = true)
    {
        $data = $this->read ($path);

        $target->write ($target_directory, $data, $replace, $create_directories);

        return;
    }

    /**
     * Moves the file identified in path to the location identified in the new_parent_directory
     *  replacing existing contents, if indicated, and creating directories needed, if indicated
     *
     * @param   string  $path
     * @param   File    $target
     * @param   string  $target_directory
     * @param   bool    $replace
     * @param   bool    $create_directories
     *
     * @return  null
     * @since   1.0
     */
    public function move ($path, File $target, $target_directory, $replace = false, $create_directories = true)
    {
        $data = $this->read ($path);

        $target->write ($target_directory, $data, $replace, $create_directories);

        $this->delete ($path);

        return;
    }

    /**
     * Renames the file identified in path to new_name within the existing parent directory
     *
     * @param   string  $path
     * @param   string  $new_name
     *
     * @return  null
     * @since   1.0
     */
    public function rename ($path, $new_name)
    {
        if (true === @rename($source, $target)) {
            return;
        }

        if (!function_exists('proc_open')) {
            return $this->copyThenRemove($source, $target);
        }

        if (defined('PHP_WINDOWS_VERSION_BUILD')) {
            // Try to copy & delete - this is a workaround for random "Access denied" errors.
            $command = sprintf('xcopy %s %s /E /I /Q', escapeshellarg($source), escapeshellarg($target));
            if (0 === $this->processExecutor->execute($command)) {
                $this->remove($source);

                return;
            }
        } else {
            // We do not use PHP's "rename" function here since it does not support
            // the case where $source, and $target are located on different partitions.
            $command = sprintf('mv %s %s', escapeshellarg($source), escapeshellarg($target));
            if (0 === $this->processExecutor->execute($command)) {
                return;
            }
        }

        throw new \RuntimeException(sprintf('Could not rename "%s" to "%s".', $source, $target));
    }


    /**
     * Returns size of a file or directory specified by path. If a directory is
     * given, it's size will be computed recursively.
     *
     * @param string $path Path to the file or directory
     * @return int
     */
    public function size($path)
    {
        if (!file_exists($path)) {
            throw new \RuntimeException("$path does not exist.");
        }
        if (is_dir($path)) {
            return $this->directorySize($path);
        }

        return filesize($path);
    }

    protected function directorySize($directory)
    {
        $it = new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS);
        $ri = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);

        $size = 0;
        foreach ($ri as $file) {
            if ($file->isFile()) {
                $size += $file->getSize();
            }
        }

        return $size;
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
        $this->path = $this->normalise($path);

        if (file_exists($this->path)) {
            return unlink($this->path);
        }

        return false;
    }

    public function sha1($file)
    {
        $file = preg_replace('{[^'.$this->whitelist.']}i', '-', $file);
        if ($this->enabled && file_exists($this->root . $file)) {
            return sha1_file($this->root . $file);
        }

        return false;
    }

    public function sha256($file)
    {
        $file = preg_replace('{[^'.$this->whitelist.']}i', '-', $file);
        if ($this->enabled && file_exists($this->root . $file)) {
            return hash_file('sha256', $this->root . $file);
        }

        return false;
    }
}
