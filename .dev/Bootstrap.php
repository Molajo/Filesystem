<?php
/**
 * Filesystem
 *
 * @package    Molajo
 * @copyright  2013 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */

if (substr($_SERVER['DOCUMENT_ROOT'], - 1) == '/') {
    define('ROOT_FOLDER', $_SERVER['DOCUMENT_ROOT']);
} else {
    define('ROOT_FOLDER', $_SERVER['DOCUMENT_ROOT'] . '/');
}

$base = substr(__DIR__, 0, strlen(__DIR__) - 5);
define('BASE_FOLDER', $base);

$classMap = array(

    'Molajo\\Filesystem\\Adapter'                        => BASE_FOLDER . '/Adapter.php',
    'Molajo\\Filesystem\\CommonApi\\ConnectionInterface' => BASE_FOLDER . '/Api/ConnectionInterface.php',
    'Molajo\\Filesystem\\CommonApi\\ExceptionInterface'  => BASE_FOLDER . '/Api/ExceptionInterface.php',
    'Molajo\\Filesystem\\CommonApi\\FilesystemInterface' => BASE_FOLDER . '/Api/FilesystemInterface.php',
    'Exception\\Filesystem\\AbstractHandlerException'    => BASE_FOLDER . '/Exception/AbstractHandlerException.php',
    'Exception\\Filesystem\\RuntimeException'            => BASE_FOLDER . '/Exception/RuntimeException.php',
    'Exception\\Filesystem\\ConnectionException'         => BASE_FOLDER . '/Exception/ConnectionException.php',
    'Exception\\Filesystem\\FtpHandlerException'         => BASE_FOLDER . '/Exception/FtpHandlerException.php',
    'Exception\\Filesystem\\LocalHandlerException'       => BASE_FOLDER . '/Exception/LocalHandlerException.php',
    'Exception\\Filesystem\\MediaHandlerException'       => BASE_FOLDER . '/Exception/MediaHandlerException.php',
    'Molajo\\Filesystem\\Handler\\AbstractHandler'       => BASE_FOLDER . '/Handler/AbstractHandler.php',
    'Molajo\\Filesystem\\Handler\\Ftp'                   => BASE_FOLDER . '/Handler/Ftp.php',
    'Molajo\\Filesystem\\Handler\\Local'                 => BASE_FOLDER . '/Handler/Local.php',
    'Molajo\\Filesystem\\Handler\\Media'                 => BASE_FOLDER . '/Handler/Media.php',
    'Ftp\\Data'                                          => BASE_FOLDER . '/.dev/Tests/Ftp/Data.php',
    'Ftp\\FtpCopyTest'                                   => BASE_FOLDER . '/.dev/Tests/Ftp/FtpCopyTest.php',
    'Ftp\\FtpDeleteTest'                                 => BASE_FOLDER . '/.dev/Tests/Ftp/FtpDeleteTest.php',
    'Ftp\\FtpMoveTest'                                   => BASE_FOLDER . '/.dev/Tests/Ftp/FtpMoveTest.php',
    'Ftp\\FtpReadTest'                                   => BASE_FOLDER . '/.dev/Tests/Ftp/FtpReadTest.php',
    'Ftp\\FtpWriteTest'                                  => BASE_FOLDER . '/.dev/Tests/Ftp/FtpWriteTest.php',
    'Ftp\\FtpListTest'                                   => BASE_FOLDER . '/.dev/Tests/Ftp/FtpListTest.php',
    'Local\\Data'                                        => BASE_FOLDER . '/.dev/Tests/Local1/Data.php',
    'Local\\LocalCopyTest'                               => BASE_FOLDER . '/.dev/Tests/Local1/LocalCopyTest.php',
    'Local\\LocalDeleteTest'                             => BASE_FOLDER . '/.dev/Tests/Local1/LocalDeleteTest.php',
    'Local\\LocalMoveTest'                               => BASE_FOLDER . '/.dev/Tests/Local1/LocalMoveTest.php',
    'Local\\LocalReadTest'                               => BASE_FOLDER . '/.dev/Tests/Local1/LocalReadTest.php',
    'Local\\LocalWriteTest'                              => BASE_FOLDER . '/.dev/Tests/Local1/LocalWriteTest.php',
    'Local\\LocalListTest'                               => BASE_FOLDER . '/.dev/Tests/Local1/LocalListTest.php'
);

spl_autoload_register(
    function ($class) use ($classMap) {
        if (array_key_exists($class, $classMap)) {
            require_once $classMap[$class];
        }
    }
);
