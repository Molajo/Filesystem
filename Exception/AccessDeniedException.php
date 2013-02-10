<?php
/**
 * AccessDeniedException Exception
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem\Exception;

defined('MOLAJO') or die;

use Exception;

/**
 * AccessDeniedException
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class AccessDeniedException extends Exception implements FilesystemExceptionInterface
{
    protected $code = 304;
}
