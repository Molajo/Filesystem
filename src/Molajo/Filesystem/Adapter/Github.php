<?php
/**
 * Github Adapter for Filesystem
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 * https://github.com/ryanharkins/mysql-php-backup/blob/master/backup.php
 */
namespace Molajo\Filesystem\Adapter;

defined ('MOLAJO') or die;

use Molajo\Filesystem\Adapter;

/**
 * Github Adapter for Filesystem
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 *
 * Full interface specification:
 *  See https://github.comsrc/Molajo/Filesystem/doc/speifications.md
 */
class Github extends FilesystemAdapter
{
    /**
     * Constructor
     *
     * @param   array  $options
     *
     * @since   1.0
     */
    public function __construct ($options = array())
    {
        parent::__construct ($options);

        return;
    }

    public function connect ()
    {

    }

}
