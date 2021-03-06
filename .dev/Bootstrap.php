<?php
/**
 * Filesystem
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */

$base = substr(__DIR__, 0, strlen(__DIR__) - 5);

$classMap = array(

    'Molajo\\Filesystem\\Driver'                         => $base . '/Adapter.php',
    'Molajo\\Filesystem\\CommonApi\\ConnectionInterface' => $base . '/Api/ConnectionInterface.php',
    'Molajo\\Filesystem\\CommonApi\\ExceptionInterface'  => $base . '/Api/ExceptionInterface.php',
    'Molajo\\Filesystem\\CommonApi\\FilesystemInterface' => $base . '/Api/FilesystemInterface.php',
    'Exception\\Filesystem\\AbstractAdapterException'    => $base . '/Exception/AbstractAdapterException.php',
    'Exception\\Filesystem\\RuntimeException'            => $base . '/Exception/RuntimeException.php',
    'Exception\\Filesystem\\ConnectionException'         => $base . '/Exception/ConnectionException.php',
    'Exception\\Filesystem\\FtpAdapterException'         => $base . '/Exception/FtpAdapterException.php',
    'Exception\\Filesystem\\LocalAdapterException'       => $base . '/Exception/LocalAdapterException.php',
    'Exception\\Filesystem\\MediaAdapterException'       => $base . '/Exception/MediaAdapterException.php',
    'Molajo\\Filesystem\\Adapter\\AbstractAdapter'       => $base . '/Adapter/AbstractAdapter.php',
    'Molajo\\Filesystem\\Adapter\\Ftp'                   => $base . '/Adapter/Ftp.php',
    'Molajo\\Filesystem\\Adapter\\Local'                 => $base . '/Adapter/Local.php',
    'Molajo\\Filesystem\\Adapter\\Media'                 => $base . '/Adapter/Media.php',
    'Ftp\\Data'                                          => $base . '/.dev/Tests/Ftp/Data.php',
    'Ftp\\FtpCopyTest'                                   => $base . '/.dev/Tests/Ftp/FtpCopyTest.php',
    'Ftp\\FtpDeleteTest'                                 => $base . '/.dev/Tests/Ftp/FtpDeleteTest.php',
    'Ftp\\FtpMoveTest'                                   => $base . '/.dev/Tests/Ftp/FtpMoveTest.php',
    'Ftp\\FtpReadTest'                                   => $base . '/.dev/Tests/Ftp/FtpReadTest.php',
    'Ftp\\FtpWriteTest'                                  => $base . '/.dev/Tests/Ftp/FtpWriteTest.php',
    'Ftp\\FtpListTest'                                   => $base . '/.dev/Tests/Ftp/FtpListTest.php',
    'Local\\Data'                                        => $base . '/.dev/Tests/Local1/Data.php',
    'Local\\LocalCopyTest'                               => $base . '/.dev/Tests/Local1/LocalCopyTest.php',
    'Local\\LocalDeleteTest'                             => $base . '/.dev/Tests/Local1/LocalDeleteTest.php',
    'Local\\LocalMoveTest'                               => $base . '/.dev/Tests/Local1/LocalMoveTest.php',
    'Local\\LocalReadTest'                               => $base . '/.dev/Tests/Local1/LocalReadTest.php',
    'Local\\LocalWriteTest'                              => $base . '/.dev/Tests/Local1/LocalWriteTest.php',
    'Local\\LocalListTest'                               => $base . '/.dev/Tests/Local1/LocalListTest.php'
);

spl_autoload_register(
    function ($class) use ($classMap) {
        if (array_key_exists($class, $classMap)) {
            require_once $classMap[$class];
        }
    }
);
