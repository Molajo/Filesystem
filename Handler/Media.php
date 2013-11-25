<?php
/**
 * Media Handler for Filesystem
 *
 * @package    Molajo
 * @copyright  2013 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Filesystem\Handler;

/**
 * Media Handler for Filesystem
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2013 Amy Stephen. All rights reserved.
 * @since      1.0
 */
class Media extends Local
{
    /**
     * Constructor
     *
     * @param array $options
     *
     * @since   1.0
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);

        return;
    }
}