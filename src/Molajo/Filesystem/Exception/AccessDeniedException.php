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
    protected $code = 304;
}
