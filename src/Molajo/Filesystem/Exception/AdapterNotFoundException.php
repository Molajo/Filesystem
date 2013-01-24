<?php
/**
 * AdapterNotFoundException Exception
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem\Exception;

defined ('MOLAJO') or die;

/**
 * AdapterNotFoundException Exception
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class AdapterNotFoundException extends FileExtension implements FileExceptionInterface
{
    /**
     * Constructor.
     *
     * @param  string  $path
     *
     * @since  1.0
     */
    public function __construct($path)
    {
        parent::__construct('The file %s could not be accessed', $path);
    }
}
