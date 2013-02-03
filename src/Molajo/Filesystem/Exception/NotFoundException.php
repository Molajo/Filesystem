<?php
/**
 * NotFoundException Exception
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem\Exception;

defined('MOLAJO') or die;

use Exception;

/**
 * AdapterNotFoundException Exception
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class NotFoundException extends Exception implements FilesystemExceptionInterface
{
    protected $code = 404;
}
