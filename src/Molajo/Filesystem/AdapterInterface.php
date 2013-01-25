<?php
/**
 * Adapter Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem;

defined ('MOLAJO') or die;

use Molajo\Filesystem\Exception\FileException as FileException;

/**
 * Adapter Interface
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
interface AdapterInterface
{
    /**
     * Set Adapter
     *
     * @param   string  $adapter_name
     * @param   string  $path
     *
     * @return  string
     * @since   1.0
     * @throws  FileException
     */
    public function setAdapter ($adapter_name = '', $path = '');

    /**
     * Get Adapter
     *
     * @return  string
     * @since   1.0
     * @throws  FileException
     */
    public function getAdapter ();
}
