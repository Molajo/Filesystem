<?php
namespace Local;

use DateTime;
use DateTimeZone;
use DateInterval;
use Molajo\Filesystem\Driver as adapter;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-01-26 at 06:27:20.
 */
class LocalPermissionsTest extends Data
{
    /**
     * Initialises Adapter
     */
    protected function setUp()
    {
        parent::setUp();

        $this->adapter = 'Local';
        $this->options = array();
    }

    /**
     * @covers Molajo\Filesystem\Adapter\Local::read
     */
    public function testPermissionsTTTSuccess()
    {
        $base = substr(__DIR__, 0, strlen(__DIR__) - 5);

        $mode          = 0644;
        $this->options = array(
            'mode' => $mode
        );
        $this->path    = $base . '/.dev/Tests/Data/Testcases/test1.txt';
        $this->action  = 'changePermission';

        $adapter = new adapter($this->action, $this->path, $this->adapter, $this->options);

        $this->assertEquals('Local', $adapter->fs->adapter);

        $this->assertEquals(
            $mode,
            octdec(substr(sprintf('%o', fileperms($this->path)), - 4))
        );

        $this->assertEquals('Local', $adapter->fs->adapter);

        $this->assertEquals(true, is_readable($this->path));
        $this->assertEquals(false, is_writeable($this->path));
        $this->assertEquals(false, is_executable($this->path));

        return;
    }

    /**
     * @covers Molajo\Filesystem\Adapter\Local::changePermission
     * @expectedException Exception\Filesystem\RuntimeException
     */
    public function testPermissionsFail()
    {
        $base = substr(__DIR__, 0, strlen(__DIR__) - 5);

        $mode          = 99999;
        $this->options = array(
            'mode' => $mode
        );
        $this->path    = $base . '/.dev/Tests/Data/Testcases/test1.txt';
        $this->action  = 'changePermission';

        $adapter = new adapter($this->action, $this->path, $this->adapter, $this->options);

        return;
    }

    /**
     * @covers Molajo\Filesystem\Adapter\Local::Touch
     * @expectedException Exception\Filesystem\RuntimeException
     */
    public function testTouchSuccess()
    {
        $base = substr(__DIR__, 0, strlen(__DIR__) - 5);

        $timezone = new DateTimeZone('GMT');
        $datetime = new DateTime(null, $timezone);

        $datetime->sub(new DateInterval("PT60M"));
        $m                 = $datetime->format("Y/m/d m:i:s");
        $modification_time = strtotime($m);

        $datetime->add(new DateInterval("PT30M"));
        $a           = $datetime->format("Y/m/d m:i:s");
        $access_time = strtotime($a);

        $this->options = array(
            'modification_time' => $modification_time,
            'access_time'       => $access_time
        );

        $this->path   = $base . '/.dev/Tests/Data/Testcases/test1.txt';
        $this->action = 'touch';

        $adapter = new adapter($this->action, $this->path, $this->adapter, $this->options);

        $hold      = stat($this->path);
        $new_atime = $hold['atime'];
        $new_mtime = $hold['mtime'];

        $this->assertEquals($new_mtime, $new_mtime);
        $this->assertEquals($new_atime, $new_atime);

        return;
    }

    /**
     * @covers Molajo\Filesystem\Adapter\Local::touch
     * @expectedException Exception\Filesystem\RuntimeException
     */
    public function testTouchFail()
    {
        $base = substr(__DIR__, 0, strlen(__DIR__) - 5);

        $timezone = new DateTimeZone('GMT');
        $datetime = new DateTime(null, $timezone);

        $datetime->sub(new DateInterval("PT60M"));
        $m                 = $datetime->format("Y/m/d m:i:s");
        $modification_time = strtotime($m);

        $datetime->add(new DateInterval("PT30M"));
        $a           = $datetime->format("Y/m/d m:i:s");
        $access_time = strtotime($a);

        $modification_time = 999999999;

        $this->options = array(
            'modification_time' => $modification_time,
            'access_time'       => $access_time
        );

        $this->path   = $base . '/.dev/Tests/Data/Testcases/test1.txt';
        $this->action = 'touch';

        $adapter = new adapter($this->action, $this->path, $this->adapter, $this->options);

        $hold      = stat($this->path);
        $new_atime = $hold['atime'];
        $new_mtime = $hold['mtime'];

        $this->assertEquals($modification_time, $new_mtime);
        $this->assertEquals($access_time, $new_atime);

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
