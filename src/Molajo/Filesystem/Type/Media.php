<?php
/**
 * Media Adapter for Filesystem
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem\Type;

defined('MOLAJO') or die;

use Molajo\Filesystem\Adapter;

/**
 * Media Adapter for Filesystem
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 *
 * Full interface specification:
 *  See https://github.comsrc/Molajo/Filesystem/doc/speifications.md
 */
class Media implements File, Path, System
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
}
