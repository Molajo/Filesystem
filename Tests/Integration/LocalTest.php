<?php
namespace Test\Filesystem;
/**
 * Adapter Class
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
defined ('MOLAJO') or die;

/**
 * Adapter Class
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */

use Molajo\Filesystem\Adapter;

Class AdapterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Options
     *
     * @var    array  $options
     * @since  1.0
     */
    protected $options = array();

    /**
     * Path
     *
     * @var    string
     * @since  1.0
     */
    protected $path;

    /**
     * Adapter Name
     *
     * @var    string
     * @since  1.0
     */
    protected $adapter_name;

    /**
     * Adapter Instance
     *
     * @var    string
     * @since  1.0
     */
    protected $adapter;

    protected function setUp() {

    }

    protected function tearDown() {

    }

    /**
     * Set Adapter
     *
     * @param   string  $adapter_name
     * @param   string  $path
     *
     * @return  object  $adapter
     * @since   1.0
     * @throws  FileException
     */
    public function testgetLocalAdapter()
    {
        $path =  ROOT_FOLDER . 'Filesystem/Tests/Testcases/test1.txt';
        $options = array();
        $class = 'Molajo\\Filesystem\\Adapter';
        $connection = new $class($path, $options);

        $data    = $connection->read ();

        $this->assertEquals('text1', trim($data));
    }

    /**
     * Get Adapter
     *
     * @return  string
     * @since   1.0
     * @throws  FileException
     */
    public function getAdapter ()
    {

    }
}
