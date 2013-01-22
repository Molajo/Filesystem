<?php
/**
 * InvalidPathException Exception
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem\Exception;

defined ('MOLAJO') or die;

use RuntimeException;

/**
 * InvalidPathException Exception
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class InvalidPathException extends RuntimeException implements FileSystemExceptionInterface
{
    private $key;

    public function __construct($key, $code = 0, \Exception $previous = null)
    {
        $this->key = $key;

        parent::__construct(
            sprintf('Invalid Path: "%s" Type is not known', $key),
            $code,
            $previous
        );
    }

    public function getKey()
    {
        return $this->path;
    }
}
