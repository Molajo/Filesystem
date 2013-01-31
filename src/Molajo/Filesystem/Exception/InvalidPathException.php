<?php
/**
 * InvalidPathException Exception
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem\Exception;

use RuntimeExtension;

defined('MOLAJO') or die;
/**
 * InvalidPathException Exception
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class InvalidPathException extends \RuntimeExtension implements FileExceptionInterface
{
    protected $code = 404;
}
