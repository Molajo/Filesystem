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

define('BASE_FOLDER', __DIR__);

$classMap = array(
    'Molajo\\Filesystem\\Adapter'                                 => BASE_FOLDER . '/src/Molajo/Filesystem/Adapter.php',
    'Molajo\\Filesystem\\Adapter\\FilesystemActionsInterface'     => BASE_FOLDER . '/src/Molajo/Filesystem/Adapter/FilesystemActionsInterface.php',
    'Molajo\\Filesystem\\Adapter\\FilesystemInterface'            => BASE_FOLDER . '/src/Molajo/Filesystem/Adapter/FilesystemInterface.php',
    'Molajo\\Filesystem\\Adapter\\Fileupload'                     => BASE_FOLDER . '/src/Molajo/Filesystem/Adapter/Fileupload.php',
    'Molajo\\Filesystem\\Adapter\\MetadataInterface'              => BASE_FOLDER . '/src/Molajo/Filesystem/Adapter/MetadataInterface.php',
    'Molajo\\Filesystem\\Adapter\\SystemInterface'                => BASE_FOLDER . '/src/Molajo/Filesystem/Adapter/SystemInterface.php',
    'Molajo\\Filesystem\\Exception\\AccessDeniedException'        => BASE_FOLDER . '/src/Molajo/Filesystem/Exception/AccessDeniedException.php',
    'Molajo\\Filesystem\\Exception\\FilesystemException'          => BASE_FOLDER . '/src/Molajo/Filesystem/Exception/FilesystemException.php',
    'Molajo\\Filesystem\\Exception\\FilesystemExceptionInterface' => BASE_FOLDER . '/src/Molajo/Filesystem/Exception/FilesystemExceptionInterface.php',
    'Molajo\\Filesystem\\Exception\\NotFoundException'            => BASE_FOLDER . '/src/Molajo/Filesystem/Exception/NotFoundException.php',
    'Molajo\\Filesystem\\Type\\Local'                             => BASE_FOLDER . '/src/Molajo/Filesystem/Type/Local.php',
    'Molajo\\Filesystem\\Type\\FilesystemProperties'              => BASE_FOLDER . '/src/Molajo/Filesystem/Type/FilesystemProperties.php',
    'Integration\\Data'                                           => BASE_FOLDER . '/tests/Integration/Data.php',
    'Integration\\LocalCopyTest'                                  => BASE_FOLDER . '/tests/Integration/LocalCopyTest.php',
    'Integration\\LocalDeleteTest'                                => BASE_FOLDER . '/tests/Integration/LocalDeleteTest.php',
    'Integration\\LocalMoveTest'                                  => BASE_FOLDER . '/tests/Integration/LocalMoveTest.php',
    'Integration\\LocalReadTest'                                  => BASE_FOLDER . '/tests/Integration/LocalReadTest.php',
    'Integration\\LocalWriteTest'                                 => BASE_FOLDER . '/tests/IntegrationLocalWriteTest.php'
);
spl_autoload_register(
    function ($class) use ($classMap) {
        if (array_key_exists($class, $classMap)) {
            require_once $classMap[$class];
        }
    }
);

/*
include BASE_FOLDER . '/' . 'ClassLoader.php';
$loader = new ClassLoader();
$loader->add('Molajo', BASE_FOLDER . '/src/');
$loader->add('Integration', BASE_FOLDER . '/tests/');
$loader->register();
*/
