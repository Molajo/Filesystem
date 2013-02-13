<?php
/**
 * Filesystem
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
define('MOLAJO', 'This is a Molajo Distribution');

if (substr($_SERVER['DOCUMENT_ROOT'], - 1) == '/') {
    define('ROOT_FOLDER', $_SERVER['DOCUMENT_ROOT']);
} else {
    define('ROOT_FOLDER', $_SERVER['DOCUMENT_ROOT'] . '/');
}

$base = substr(__DIR__, 0, strlen(__DIR__) - 5);
define('BASE_FOLDER', $base);

$classMap = array(
    'Molajo\\Filesystem\\Adapter'                                 => BASE_FOLDER . '/Adapter.php',
    'Molajo\\Filesystem\\Adapter\\FilesystemActionsInterface'     => BASE_FOLDER . '/Adapter/FilesystemActionsInterface.php',
    'Molajo\\Filesystem\\Adapter\\FilesystemInterface'            => BASE_FOLDER . '/Adapter/FilesystemInterface.php',
    'Molajo\\Filesystem\\Adapter\\Fileupload'                     => BASE_FOLDER . '/Adapter/Fileupload.php',
    'Molajo\\Filesystem\\Adapter\\MetadataInterface'              => BASE_FOLDER . '/Adapter/MetadataInterface.php',
    'Molajo\\Filesystem\\Adapter\\SystemInterface'                => BASE_FOLDER . '/Adapter/SystemInterface.php',
    'Molajo\\Filesystem\\Exception\\AccessDeniedException'        => BASE_FOLDER . '/Exception/AccessDeniedException.php',
    'Molajo\\Filesystem\\Exception\\FilesystemException'          => BASE_FOLDER . '/Exception/FilesystemException.php',
    'Molajo\\Filesystem\\Exception\\FilesystemExceptionInterface' => BASE_FOLDER . '/Exception/FilesystemExceptionInterface.php',
    'Molajo\\Filesystem\\Exception\\NotFoundException'            => BASE_FOLDER . '/Exception/NotFoundException.php',
    'Molajo\\Filesystem\\Type\\Local'                             => BASE_FOLDER . '/Type/Local.php',
    'Molajo\\Filesystem\\Type\\Ftp'                               => BASE_FOLDER . '/Type/Ftp.php',
    'Molajo\\Filesystem\\Type\\FilesystemProperties'              => BASE_FOLDER . '/Type/FilesystemProperties.php',

    'Ftp\\Data'                                                   => BASE_FOLDER . '/.dev/Tests/Ftp/Data.php',
    'Ftp\\FtpCopyTest'                                            => BASE_FOLDER . '/.dev/Tests/Ftp/FtpCopyTest.php',
    'Ftp\\FtpDeleteTest'                                          => BASE_FOLDER . '/.dev/Tests/Ftp/FtpDeleteTest.php',
    'Ftp\\FtpMoveTest'                                            => BASE_FOLDER . '/.dev/Tests/Ftp/FtpMoveTest.php',
    'Ftp\\FtpReadTest'                                            => BASE_FOLDER . '/.dev/Tests/Ftp/FtpReadTest.php',
    'Ftp\\FtpWriteTest'                                           => BASE_FOLDER . '/.dev/Tests/Ftp/FtpWriteTest.php',
    'Ftp\\FtpListTest'                                            => BASE_FOLDER . '/.dev/Tests/Ftp/FtpListTest.php',

    'Local\\Data'                                                 => BASE_FOLDER . '/.dev/Tests/Local/Data.php',
    'Local\\LocalCopyTest'                                        => BASE_FOLDER . '/.dev/Tests/Local/LocalCopyTest.php',
    'Local\\LocalDeleteTest'                                      => BASE_FOLDER . '/.dev/Tests/Local/LocalDeleteTest.php',
    'Local\\LocalMoveTest'                                        => BASE_FOLDER . '/.dev/Tests/Local/LocalMoveTest.php',
    'Local\\LocalReadTest'                                        => BASE_FOLDER . '/.dev/Tests/Local/LocalReadTest.php',
    'Local\\LocalWriteTest'                                       => BASE_FOLDER . '/.dev/Tests/Local/LocalWriteTest.php',
    'Local\\LocalListTest'                                        => BASE_FOLDER . '/.dev/Tests/Local/LocalListTest.php'

);

spl_autoload_register(
    function ($class) use ($classMap) {
        if (array_key_exists($class, $classMap)) {
            require_once $classMap[$class];
        }
    }
);

