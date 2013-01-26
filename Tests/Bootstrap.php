<?php
/**
 * Filesystem
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */

class FilesystemTests extends \PHPUnit_Framework_TestCase
{
    /**
     * @return array Record
     */
    protected function start($path, $options=array())
    {
        return array(
            'path' => 'path\to\here.txt',
            'options' => array(),
            'datetime' => \DateTime::createFromFormat('U.u', sprintf('%.6F', microtime(true))),
            'extra' => array(),
        );
    }

}
