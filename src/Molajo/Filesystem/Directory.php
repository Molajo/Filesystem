<?php
/**
 * Path Instance for Filesystem
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem;

defined ('MOLAJO') or die;

use Molajo\Filesystem\Directory as DirectoryInterface;

/**
 * Path Instance for Filesystem
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
Interface Directory
{
    /**
     * Returns a list of files (path and filename) located at path directory
     *
     * @param   string  $path
     *
     * @return  null
     * @since   1.0
     */
    function getList ($path, $file_name_mask = '', $extension = '', $recursive = true);

    /**
     * Deletes the file identified in path. Empty directories are removed if so indicated
     *
     * @param   string  $path
     * @param   bool    $delete_empty_directory
     *
     * @return  null
     * @since   1.0
     */
     function create ($path, $name, $permission);
}
