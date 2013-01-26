<?php
/**
 * Path Interface for Filesystem Adapter
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem;

defined ('MOLAJO') or die;

/**
 * Path Interface for Filesystem Adapter
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
interface PathInterface
{
    /**
     * Sets the value of the path defining the current directory and file
     *
     * @param $path
     *
     * @return  string
     * @since   1.0
     */
    public function setPath ($path);

    /**
     * Returns the value of the path defining the current directory and file
     *
     * @return  string
     * @since   1.0
     */
    public function getPath ();

    /**
     * Retrieves the absolute path, which is the relative path from the root directory
     *
     * @param   string  $path
     *
     * @return  string
     * @since   1.0
     */
    public function getAbsolutePath ($path);

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
    public function isAbsolute ($path);

    /**
     * Returns the value 'directory', 'file' or 'link' for the type determined
     *  prepended with a '/'.
     *
     * @param   string  $path
     *
     * @return  string
     * @since   1.0
     */
    public function getType ($path);

    /**
     * Set persistence indicator for Filesystem
     *
     * @param   bool  $persistence
     *
     * @return  string
     * @since   1.0
     */
    public function setPersistence ($persistence);

    /**
     * Returns true or false indicator as to whether or not the path is a directory
     *
     * @param   string  $path
     *
     * @return  bool
     * @since   1.0
     */
    public function isDirectory ($path);

    /**
     * Returns true or false indicator as to whether or not the path is a file
     *
     * @param   string  $path
     *
     * @return  bool
     * @since   1.0
     */
    public function isFile ($path);

    /**
     * Returns true or false indicator as to whether or not the path is a link
     *
     * @param   string  $path
     *
     * @return  bool
     * @since   1.0
     */
    public function isLink ($path);

    /**
     * Determine if the file or directory specified in path exists
     *
     * @param   string  $path
     *
     * @return  bool
     * @since   1.0
     */
    public function exists ($path);

    /**
     * Get File or Directory Name
     *
     * @param   string  $path
     *
     * @return  string
     * @since   1.0
     */
    public function getName ($path);

    /**
     * Get Parent for current path
     *
     * @param   string  $path
     *
     * @return  string
     * @since   1.0
     */
    public function getParent ($path);

    /**
     * Get File Extension
     *
     * @param   string  $path
     *
     * @return  string
     * @since   1.0
     */
    public function getExtension ($path);

    /**
     * Get the file size of a given file.
     *
     * @param   $path
     *
     * @return  string
     * @since   1.0
     */
    public function size ($path = '');

    /**
     * Normalizes the given path
     *
     * @param   $path
     *
     * @return  string
     * @since   1.0
     */
    public function normalise ($path = '');
}
