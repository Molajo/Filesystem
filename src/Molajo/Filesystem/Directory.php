<?php
/**
 * Directory Instance for Fileservices
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem;

defined ('MOLAJO') or die;

use Molajo\Filesystem\Entry as EntryInterface;

/**
 * Filesystem Directory Instance
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 *
 * Full interface specification:
 *  See https://github.com/Molajo/Filesystem/doc/speifications.md
 */
Interface Directory extends EntryInterface
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
