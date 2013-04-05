<?php
/**
 * Metadata Interface for Filesystem
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Filesystem\Adapter;

defined('MOLAJO') or die;

/**
 * Metadata Interface for Filesystem which further defines the getMetadata method for the Adapter
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @since     1.0
 */
interface MetadataInterface
{

    /**
     * Does the path exist (as either a file or a folder)?
     *
     * @return void
     */
    public function exists();

    /**
     * Determine the relative path for the current path
     *
     * @param string $absolute_path_of_target
     *
     * @return void
     * @since   1.0
     */
    public function getRelativePath($absolute_path_of_target = '');

    /**
     * Retrieves the absolute path, which is the relative path from the root directory
     *
     * @return void
     * @since   1.0
     */
    public function getAbsolutePath();

    /**
     * Indicates whether the given path is absolute or not
     *
     * Relative Path - describes how to get from a particular directory to a file or directory
     * Absolute Path - relative path from the root directory, prepended with a '/'.
     *
     * @return void
     * @since   1.0
     */
    public function isAbsolutePath();

    /**
     * Returns true or false indicator as to whether or not the path is a directory
     *
     * @return void
     * @since   1.0
     */
    public function isDirectory();

    /**
     * Returns true or false indicator as to whether or not the path is a file
     *
     * @return void
     * @since   1.0
     */
    public function isFile();

    /**
     * Returns true or false indicator as to whether or not the path is a link
     *
     * @return void
     * @since   1.0
     */
    public function isLink();

    /**
     * Returns the value 'directory', 'file' or 'link' for the type determined
     *  dependent upon isDirectory, isFile, isLink
     *
     * @return void
     * @since   1.0
     */
    public function getType();

    /**
     * Get File or Directory Name
     *
     * @return void
     * @since   1.0
     */
    public function getName();

    /**
     * Get Parent for current path
     *
     * @return void
     * @since   1.0
     */
    public function getParent();

    /**
     * Get File Extension
     *
     * @return void
     * @since   1.0
     */
    public function getExtension();

    /**
     * Get Filename without Extension
     *
     * @return void
     * @since   1.0
     */
    public function getNoExtension();

    /**
     * Get the file size of a given file.
     *
     * @return void
     * @since   1.0
     */
    public function getSize();

    /**
     * Returns the mime type of the file located at path directory
     *
     * @return void
     * @since   1.0
     */
    public function getMimeType();

    /**
     * Returns the owner of the file or directory defined in the path
     *
     * @return void
     * @since   1.0
     */
    public function getOwner();

    /**
     * Returns the group for the file or directory defined in the path
     *
     * @return void
     * @since   1.0
     */
    public function getGroup();

    /**
     * Retrieves Create Date for directory or file identified in the path
     *
     * @return  object Datetime
     * @since   1.0
     */
    public function getCreateDate();

    /**
     * Retrieves Last Update Date for directory or file identified in the path
     *
     * @return  object Datetime
     * @since   1.0
     */
    public function getModifiedDate();

    /**
     * Retrieves Last Access Date for directory or file identified in the path
     *
     * @return  object Datetime
     * @since   1.0
     */
    public function getAccessDate();

    /**
     * Tests for read access, returning true or false
     *
     * @return void
     * @since   1.0
     */
    public function isReadable();

    /**
     * Tests for write access, returning true or false
     *
     * @return void
     * @since   1.0
     */
    public function isWriteable();

    /**
     * Tests for execute access, returning true or false
     *
     * @return void
     * @since   1.0
     */
    public function isExecutable();
}
