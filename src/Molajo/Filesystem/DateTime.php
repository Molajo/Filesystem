<?php
namespace Molajo\Filesystem\Type;

use \DateTime as phpDateTime;

/**
 * Local Adapter for Filesystem
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
defined('MOLAJO') or die;

/**
 * Molajo Filesystem Datetime
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class Datetime extends phpDateTime
{
    function __construct($time = null, DateTimeZone $timezone = null)
    {
        if ($time instanceof DateTime) {
            $time = $time->getTimestamp();
        }

        if (is_int($time) && $time > 0) {

            parent::__construct(null, $timezone);
            $this->setTimestamp($time);

        } else {

            parent::__construct($time, $timezone);
        }

        return;
    }
}
