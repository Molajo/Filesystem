<?php
/**
 * Foundation Test
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2013 Amy Stephen. All rights reserved.
 */
namespace Local;

use Molajo\Filesystem\Adapter as adapter;

/**
 * Foundation Test
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2013 Amy Stephen. All rights reserved.
 * @since      1.0
 */
class LocalCopyTest extends Data
{
    /**
     * Application ID
     *
     * @var    string
     * @since  1.0
     */
    private $class;

    /**
     * Setup
     *
     * @return  $this
     * @since   1.0
     */
    protected function setUp()
    {
        parent::setUp();

        /** initialise call */
        $this->handler = 'Local';
        $this->action  = 'Copy';

        return;
    }

    /**
     * Should default target directory to $this->path
     *
     * @covers Molajo\Filesystem\Handler\Local::copyOrMove
     */
    public function testCopySuccessfulSingleFileBlankDirectory()
    {
        $temp = BASE_FOLDER . '/.dev/Tests/Data/Testcases';

        $this->options = array(
            'target_directory' => '',
            'target_name'      => 'test2.txt',
            'replace'          => false,
            'target_handler'   => 'Local'
        );
        $this->path    = BASE_FOLDER . '/.dev/Tests/Data/Testcases/test1.txt';

        $adapter = new adapter($this->action, $this->path, $this->handler, $this->options);

        $this->assertEquals($this->calculateSize($this->path), $this->calculateSize($temp . '/test2.txt'));
        $this->assertGreaterThan(0, $this->calculateSize($temp . '/test2.txt'));
    }

    /**
     * @covers Molajo\Filesystem\Handler\Local::copyOrMove
     */
    public function testCopySuccessfulSingleFile()
    {
        $temp = BASE_FOLDER . '/.dev/Tests/Data/Testcases';

        $this->options = array(
            'target_directory' => '',
            'target_name'      => 'test2.txt',
            'replace'          => false,
            'target_handler'   => 'Local'
        );
        $this->path    = BASE_FOLDER . '/.dev/Tests/Data/Testcases/test1.txt';

        $adapter = new adapter($this->action, $this->path, $this->handler, $this->options);

        $this->assertEquals($this->calculateSize($this->path), $this->calculateSize($temp . '/test2.txt'));
        $this->assertGreaterThan(0, $this->calculateSize($temp . '/test2.txt'));

        return;
    }

    /**
     * @covers Molajo\Filesystem\Handler\Local::copyOrMove
     */
    public function testCopySingleFolder()
    {
        $temp = BASE_FOLDER . '/.dev/Tests/Data';

        $this->options = array(
            'target_directory' => $temp,
            'target_name'      => 'didit',
            'replace'          => false,
            'target_handler'   => 'Local'
        );
        $this->path    = BASE_FOLDER . '/.dev/Tests/Data/doit';

        $adapter = new adapter($this->action, $this->path, $this->handler, $this->options);

        $this->assertEquals($this->calculateSize($this->path), $this->calculateSize($temp . '/didit'));
        $this->assertGreaterThan(0, $this->calculateSize($temp . '/didit'));

        return;
    }

    /**
     * @covers Molajo\Filesystem\Handler\Local::copyOrMove
     */
    public function testCopyMultipleFolder()
    {
        $temp = BASE_FOLDER . '/.dev/Tests/Data';

        $this->options = array(
            'target_directory' => $temp,
            'target_name'      => 'Amy',
            'replace'          => false,
            'target_handler'   => 'Local'
        );
        $this->path    = BASE_FOLDER . '/.dev/Tests/Data/Testcases';

        $adapter = new adapter($this->action, $this->path, $this->handler, $this->options);

        $this->assertEquals($this->calculateSize($this->path), $this->calculateSize($temp . '/Amy'));
        $this->assertGreaterThan(0, $this->calculateSize($temp . '/Amy'));

        return;
    }

    /**
     * @covers Molajo\Filesystem\Handler\Local::copyOrMove
     * @expectedException Exception\Filesystem\RuntimeException
     */
    public function testCopyNotAFile()
    {
        $this->options = array(
            'target_directory' => BASE_FOLDER . '/.dev/Tests/Data',
            'target_name'      => 'Amy',
            'replace'          => false,
            'target_handler'   => 'Local'
        );
        $this->path    = BASE_FOLDER . '/.dev/Tests/Dataeeeeee';
        $adapter       = new adapter($this->action, $this->path, $this->handler, $this->options);

        return;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        parent::tearDown();
    }
}
