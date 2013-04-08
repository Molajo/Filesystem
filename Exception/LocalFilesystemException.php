<?php
/**
 * LocalFilesystemException
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Filesystem\Exception;

defined('MOLAJO') or die;

use Exception;

use Molajo\Filesystem\Api\ExceptionInterface;

/**
 * LocalFilesystemException Exception
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class LocalFilesystemException extends Exception implements ExceptionInterface
{

}
