<?php
/**
 * AccessDeniedException Exception
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem\Exception;

defined ('MOLAJO') or die;

/**
 * AccessDeniedException Exception
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class AccessDeniedException extends FileException
{
    /**
     * Constructor.
     *
     * @param  string  $path
     *
     * @since  1.0
     */
    public function __construct ($path)
    {
        parent::__construct ('The file %s could not be accessed', $path);
    }
}
