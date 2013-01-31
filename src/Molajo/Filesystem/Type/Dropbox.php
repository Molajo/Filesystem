<?php
/**
 * Dropbox Adapter for Filesystem
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem\Type;

defined('MOLAJO') or die;

use Molajo\Filesystem\Adapter;

/**
 * Dropbox Adapter for Filesystem
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 *
 * http://code.google.com/p/dropbox-php/wiki/Dropbox_API
 */
class Dropbox implements File, Path, System
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
