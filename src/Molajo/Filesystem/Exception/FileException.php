<?php
/**
 * FileException Exception
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem\Exception;

defined ('MOLAJO') or die;

/**
 * FileException Exception
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class FileException extends \RuntimeException implements FileSystemExceptionInterface
{
    private $key;

    public function __construct($key, $code = 0, \Exception $previous = null)
    {
        $this->key = $key;

        parent::__construct(
            sprintf('File "%s" Not found.', $key),
            $code,
            $previous
        );
    }

    public function getKey()
    {
        return $this->path;
    }
}
