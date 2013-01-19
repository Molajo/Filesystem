<?php
/**
 * Filesystem Exception
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem;

defined ('MOLAJO') or die;

/**
 * Filesystem Exception
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 *
 * Full interface specification:
 *  See https://github.comsrc/Molajo/Filesystem/doc/speifications.md
 */
Class FilesystemException extends \Exception
{

    /**
     * Construct
     *
     * @return  void
     * @since   1.0
     */
    public function __construct ()
    {

    }

}


class FileNotFoundException extends \Exception {}
