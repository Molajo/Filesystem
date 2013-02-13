<?php
/**
 * Github Adapter for Filesystem
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 *            https://github.com/ryanharkins/mysql-php-backup/blob/master/backup.php
 */
namespace Molajo\Filesystem\Type;

defined('MOLAJO') or die;

use Molajo\Filesystem\Adapter;

/**
 * Github Adapter for Filesystem
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 *
 * Full interface specification:
 *  See https://github.comsrc/Molajo/Filesystem/doc/speifications.md
 */
class Github implements File, Path, System
{
    /**
     * Constructor
     *
     * @param   array  $options
     *
     * @since   1.0
     */
    public function __construct($options = array())
    {
        parent::__construct($options);

        return;
    }

    public function connect()
    {

    }

}
