<?php
/**
 * Path Adapter for Filesystem
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem;

defined('MOLAJO') or die;

use Exception;

use Molajo\Filesystem\Exception\AccessDeniedException as AccessDeniedException;
use Molajo\Filesystem\Exception\AdapterNotFoundException as AdapterNotFoundException;
use Molajo\Filesystem\Exception\FileException as FileException;
use Molajo\Filesystem\Exception\FileExceptionInterface as FileExceptionInterface;
use Molajo\Filesystem\Exception\FileNotFoundException as FileNotFoundException;
use Molajo\Filesystem\Exception\InvalidPathException as InvalidPathException;

/**
 * Path Adapter for Filesystem
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class Path extends System implements PathInterface
{
    /**
     * Constructor
     *
     * @param   string  $path
     * @param  array   $options
     *
     * @since  1.0
     */
    public function __construct($path, $options = array())
    {
        parent::__construct($path, $options);

        $this->setPath($path);

        return;
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
        $path = $this->normalise($path);

        return $this->path = $path;
    }

    /**
     * Get the Path
     *
     * @return  string
     * @since   1.0
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Retrieves the absolute path, which is the relative path from the root directory,
     *  prepended with a '/'.
     *
     * @param   string  $path
     *
     * @return  string
     * @since   1.0
     * @throws  FileNotFoundException
     */
    public function getAbsolutePath($path)
    {
        if ($path == '') {
            $path = $this->path;
        }

        $path = $this->normalise($path);

        if ($this->exists($path) === false) {
            throw new FileNotFoundException
            ('Filesystem: getAbsolutePath method does not exist: ' . $path);
        }

        $this->absolute_path = realpath($path);

        return $this->absolute_path;
    }

    /**
     * Indicates whether the given path is absolute or not
     *
     * Relative path - describes how to get from a particular directory to a file or directory
     * Absolute Path - relative path from the root directory, prepended with a '/'.
     *
     * @param   string  $path
     *
     * @return  bool
     * @since   1.0
     */
    public function isAbsolute($path)
    {
        if (substr($this->path, 0, 1) == '/') {
            return true;
        }

        return false;
    }

    /**
     * Returns the value 'directory, 'file' or 'link' for the type determined
     *  from the path
     *
     * @param   string  $path
     *
     * @return  null
     * @since   1.0
     * @throws  FileException
     * @throws  FileNotFoundException
     */
    public function getType($path)
    {
        if ($path == '') {
            $path = $this->path;
        }

        $path = $this->normalise($path);

        if ($this->exists($path) === false) {
            throw new FileNotFoundException
            ('Filesystem: getName method does not exist: ' . $path);
        }

        if ($this->isDirectory($path)) {
            return 'directory';
        }

        if ($this->isFile($path)) {
            return 'file';
        }

        if ($this->isLink($path)) {
            return 'link';
        }

        throw new FileException ('Not a directory, file or a link.');
    }

    /**
     * Set persistence indicator for Filesystem
     *
     * @param   bool  $persistence
     *
     * @return  null
     * @since   1.0
     */
    public function setPersistence($persistence)
    {
        $this->persistence = $persistence;

        return $this->persistence;
    }


    /**
     * Returns true or false indicator as to whether or not the path is a directory
     *
     * @param   string  $path
     *
     * @return  bool
     * @since   1.0
     */
    public function isDirectory($path)
    {
        if ($path == '') {
            $path = $this->path;
        }

        $path = $this->normalise($path);

        if (is_file($path)) {
            return true;
        }

        return false;
    }

    /**
     * Returns true or false indicator as to whether or not the path is a file
     *
     * @param   string  $path
     *
     * @return  bool
     * @since   1.0
     */
    public function isFile($path)
    {
        if ($path == '') {
            $path = $this->path;
        }

        $path = $this->normalise($path);

        if (is_file($path)) {
            return true;
        }

        return false;
    }

    /**
     * Returns true or false indicator as to whether or not the path is a link
     *
     * @param   string  $path
     *
     * @return  bool
     * @since   1.0
     */
    public function isLink($path)
    {
        if ($path == '') {
            $path = $this->path;
        }

        $path = $this->normalise($path);

        if (is_link($path)) {
            return true;
        }

        return false;
    }

    /**
     * Does the path exist (either as a file or a folder)?
     *
     * @param string $path
     *
     * @return bool|null
     */
    public function exists($path)
    {
        if ($path == '') {
            $path = $this->path;
        }

        $path = $this->normalise($path);

        if (file_exists($path)) {
            return true;
        }

        return false;
    }

    /**
     * Get File or Directory Name
     *
     * @param   string $path
     *
     * @return  bool|null
     * @since   1.0
     * @throws  FileNotFoundException
     */
    public function getName($path)
    {
        if ($path == '') {
            $path = $this->path;
        }

        $path = $this->normalise($path);

        if ($this->exists($path) === false) {
            throw new FileNotFoundException
            ('Filesystem: getName method Path does not exist: ' . $path);
        }

        return basename($path);
    }

    /**
     * Get Parent
     *
     * @param   string $path
     *
     * @return  bool|null
     * @since   1.0
     * @throws  FileNotFoundException
     */
    public function getParent($path)
    {
        if ($path == '') {
            $path = $this->path;
        }

        $path = $this->normalise($path);

        if ($this->exists($path) === false) {
            throw new FileNotFoundException
            ('Filesystem: getParent method Path does not exist: ' . $path);
        }

        return dirname($path);
    }

    /**
     * Get File Extension
     *
     * @param   string $path
     *
     * @return  bool|null
     * @since   1.0
     * @throws  FileNotFoundException
     */
    public function getExtension($path)
    {
        if ($path == '') {
            $path = $this->path;
        }

        $path = $this->normalise($path);

        if ($this->exists($path) === false) {
            throw new FileNotFoundException
            ('Filesystem: getExtension method Path does not exist: ' . $path);
        }

        $path = $this->normalise($path);

        if ($this->exists($path) === false) {
            throw new FileNotFoundException ('Filesystem: could not find file at path: ', $path);
        }

        if ($this->isFile($path)) {
        } else {
            throw new FileNotFoundException ('Filesystem: not a valid file path: ', $path);
        }

        return pathinfo(basename($path), PATHINFO_EXTENSION);
    }

    /**
     * Get the file size of a given file.
     *
     * @param   string  $path
     *
     * @return  int
     * @since   1.0
     */
    public function size($path = '')
    {
        if ($path == '') {
            $path = $this->path;
        }

        return filesize($path);
    }

    /**
     * Normalizes the given path
     *
     * @param   $path
     *
     * @return  string
     * @since   1.0
     * @throws  OutOfBoundsException
     */
    public function normalise($path = '')
    {
        if ($path == '') {
            $path = $this->path;
        }

        $absolute_path = false;
        if (substr($path, 0, 1) == '/') {
            $absolute_path = true;
            $path          = substr($path, 1, strlen($path));
        }

        /** Unescape slashes */
        $path = str_replace('\\', '/', $path);

        /**  Filter: empty value
         *
         * @link http://tinyurl.com/arrayFilterStrlen
         */
        $nodes = array_filter(explode('/', $path), 'strlen');

        $normalised = array();

        foreach ($nodes as $node) {

            /** '.' means current - ignore it      */
            if ($node == '.') {

                /** '..' is parent - remove the parent */
            } elseif ($node == '..') {

                if (count($normalised) > 0) {
                    array_pop($normalised);
                }

            } else {
                $normalised[] = $node;
            }

        }

        $path = implode('/', $normalised);
        if ($absolute_path === true) {
            $path = '/' . $path;
        }

        if (0 !== strpos($path, $this->directory)) {
            throw new \OutOfBoundsException(sprintf('The path "%s" is out of the filesystem.', $path));
        }

        return $path;
    }
}
