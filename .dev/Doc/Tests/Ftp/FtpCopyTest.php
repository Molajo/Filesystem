<?php
namespace Ftp;

use Molajo\Filesystem\Adapter as fsAdapter;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-01-26 at 06:27:20.
 */
class FtpCopyTest extends Data
{
    private $class;

    /**
     * Initialises Adapter
     */
    protected function setUp()
    {
        parent::setUp();

        /** initialise call */
        $this->filesystem_type = 'Ftp';
        $this->action          = 'Copy';

        return;
    }

    /**
     * Should default target directory to $this->path
     *
     * @covers Molajo\Filesystem\Type\Ftp::copyOrMove
     */
    public function testCopySuccessfulSingleFileBlankDirectory()
    {
        $temp = BASE_FOLDER . '/.dev/Tests/Data/Testcases';

        $this->options = array(
            'target_directory'       => '',
            'target_name'            => 'test2.txt',
            'replace'                => false,
            'target_filesystem_type' => 'Ftp'
        );
        $this->path    = BASE_FOLDER . '/.dev/Tests/Data/Testcases/test1.txt';

        $adapter = new fsAdapter($this->action, $this->path, $this->filesystem_type, $this->options);

        $this->assertEquals($this->calculateSize($this->path), $this->calculateSize($temp . '/test2.txt'));
        $this->assertGreaterThan(0, $this->calculateSize($temp . '/test2.txt'));
    }

    /**
     * @covers Molajo\Filesystem\Type\Ftp::copyOrMove
     */
    public function testCopySuccessfulSingleFile()
    {
        $temp = BASE_FOLDER . '/.dev/Tests/Data/Testcases';

        $this->options = array(
            'target_directory'       => '',
            'target_name'            => 'test2.txt',
            'replace'                => false,
            'target_filesystem_type' => 'Ftp'
        );
        $this->path    = BASE_FOLDER . '/.dev/Tests/Data/Testcases/test1.txt';

        $adapter = new fsAdapter($this->action, $this->path, $this->filesystem_type, $this->options);

        $this->assertEquals($this->calculateSize($this->path), $this->calculateSize($temp . '/test2.txt'));
        $this->assertGreaterThan(0, $this->calculateSize($temp . '/test2.txt'));

        return;
    }

    /**
     * @covers Molajo\Filesystem\Type\Ftp::copyOrMove
     */
    public function testCopySingleFolder()
    {
        $temp = BASE_FOLDER . '/.dev/Tests/Data';

        $this->options = array(
            'target_directory'       => $temp,
            'target_name'            => 'didit',
            'replace'                => false,
            'target_filesystem_type' => 'Ftp'
        );
        $this->path    = BASE_FOLDER . '/.dev/Tests/Data/doit';

        $adapter = new fsAdapter($this->action, $this->path, $this->filesystem_type, $this->options);

        $this->assertEquals($this->calculateSize($this->path), $this->calculateSize($temp . '/didit'));
        $this->assertGreaterThan(0, $this->calculateSize($temp . '/didit'));

        return;
    }

    /**
     * @covers Molajo\Filesystem\Type\Ftp::copyOrMove
     */
    public function testCopyMultipleFolder()
    {
        $temp = BASE_FOLDER . '/.dev/Tests/Data';

        $this->options = array(
            'target_directory'       => $temp,
            'target_name'            => 'Amy',
            'replace'                => false,
            'target_filesystem_type' => 'Ftp'
        );
        $this->path    = BASE_FOLDER . '/.dev/Tests/Data/Testcases';

        $adapter = new fsAdapter($this->action, $this->path, $this->filesystem_type, $this->options);

        $this->assertEquals($this->calculateSize($this->path), $this->calculateSize($temp . '/Amy'));
        $this->assertGreaterThan(0, $this->calculateSize($temp . '/Amy'));

        return;
    }

    /**
     * @covers Molajo\Filesystem\Type\Ftp::copyOrMove
     * @expectedException Molajo\Filesystem\Exception\FilesystemException
     */
    public function testCopyNotAFile()
    {
        $this->options = array(
            'target_directory'       => BASE_FOLDER . '/.dev/Tests/Data',
            'target_name'            => 'Amy',
            'replace'                => false,
            'target_filesystem_type' => 'Ftp'
        );
        $this->path    = BASE_FOLDER . '/.dev/Tests/Dataeeeeee';
        $adapter       = new fsAdapter($this->action, $this->path, $this->filesystem_type, $this->options);

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
