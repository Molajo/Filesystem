<?php
/**
 * File Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem;

defined ('MOLAJO') or die;

/**
 * Describes a file instance for the filesystem
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
Interface File extends Path
{
    /**
     * Set the name of the file specified in the path
     *
     * @param   string  $path
     *
     * @return  null
     * @since   1.0
     */
    function setName ($path);

    /**
     * Get the name of the file specified in the path
     *
     * @return  null
     * @since   1.0
     */
    function getName ();

    /**
     * Retrieves the extension type for the file identified in the path
     *
     * @param   string $path
     *
     * @return  null
     * @since   1.0
     */
    public function setExtension ($path);

    /**
     * Retrieves the extension type for the file identified in the path
     *
     * @return  null
     * @since   1.0
     */
    public function getExtension ();

    /**
     * Set metadata for the file specified in path and returns an
     *  associative array minimally populated with: last_accessed_date,
     *  last_updated_date, size, mimetype, absolute_path, relative_path,
     *  filename, and file_extension.
     *
     * @param   string  $path
     *
     * @return  null
     * @since   1.0
     */
    public function setMetadata ($path);

    /**
     * Retrieves metadata for the file specified in path
     *
     * @return  null
     * @since   1.0
     */
    public function getMetadata ();

    /**
     * Returns the contents of the file located at path directory
     *
     * @param   string  $path
     *
     * @return  null
     * @since   1.0
     */
    public function read ($path);

    /**
     * Creates or replaces the file identified in path using the data value
     *
     * @param   string  $path
     * @param           $data
     * @param   bool    $replace
     * @param   bool    $create_directories
     *
     * @return  null
     * @since   1.0
     */
    public function write ($path, $data, $replace = false, $create_directories = true);

    /**
     * Copies the file identified in $path to the new_parent_directory,
     *  replacing existing contents, if indicated, and creating directories needed, if indicated
     *
     * @param   string  $path
     * @param   string  $new_parent_directory
     * @param   bool    $replace
     * @param   bool    $create_directories
     *
     * @return  null
     * @since   1.0
     */
    public function copy ($path, $new_parent_directory, $replace = false, $create_directories = true);

    /**
     * Moves the file identified in path to the location identified in the new_parent_directory
     *  replacing existing contents, if indicated, and creating directories needed, if indicated
     *
     * @param   string  $path
     * @param   string  $new_parent_directory
     * @param   bool    $replace
     * @param   bool    $create_directories
     *
     * @return  null
     * @since   1.0
     */
    public function move ($path, $new_parent_directory, $replace = false, $create_directories = true);

    /**
     * Renames the file identified in path to new_name within the existing parent directory
     *
     * @param   string  $path
     * @param   string  $new_name
     *
     * @return  null
     * @since   1.0
     */
    public function rename ($path, $new_name);

    /**
     * Deletes the file identified in path. Empty directories are removed if so indicated
     *
     * @param   string  $path
     * @param   bool    $delete_empty_directory
     *
     * @return  null
     * @since   1.0
     */
    public function delete ($path, $delete_empty_directory = true);
}
