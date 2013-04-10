<?php
/**
 * Abstract Adapter Exception
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
 * Abstract Adapter Exception
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class AbstractHandlerException extends Exception implements ExceptionInterface
{

}
