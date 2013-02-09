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

include BASE_FOLDER . '/' . 'ClassLoader.php';

$loader = new ClassLoader();

/**
$loader->add('Molajo\\Filesystem\\Adapter',
    BASE_FOLDER . '/Src/Molajo/Filesystem/Adapter.php');

$loader->add('Molajo\\Filesystem\\Adapter\\FilesystemInterface',
    BASE_FOLDER . '/Src/Molajo/Filesystem/Adapter/FilesystemInterface.php');
$loader->add('Molajo\\Filesystem\\Adapter\\FilesystemActionsInterface',
    BASE_FOLDER . '/Src/Molajo/Filesystem/Adapter/FilesystemActionsInterface.php');
$loader->add('Molajo\\Filesystem\\Adapter\\MetadataInterface',
    BASE_FOLDER . '/Src/Molajo/Filesystem/Adapter/MetadataInterface.php');
$loader->add('Molajo\\Filesystem\\Adapter\\Systeminterface',
    BASE_FOLDER . '/Src/Molajo/Filesystem/Adapter/Systeminterface.php');

$loader->add('Molajo\\Filesystem\\Exception\\AccessDeniedException',
    BASE_FOLDER . '/Src/Molajo/Filesystem/Exception/AccessDeniedException.php');
$loader->add('Molajo\\Filesystem\\Exception\\FilesystemException',
    BASE_FOLDER . '/Src/Molajo/Filesystem/Exception/FilesystemException.php');
$loader->add('Molajo\\Filesystem\\Exception\\FilesystemExceptionInterface',
    BASE_FOLDER . '/Src/Molajo/Filesystem/Exception/FilesystemExceptionInterface.php');
$loader->add('Molajo\\Filesystem\\Exception\\NotFoundException',
    BASE_FOLDER . '/Src/Molajo/Filesystem/Exception/NotFoundException.php');

$loader->add('Molajo\\Filesystem\\Type\\Local',
    BASE_FOLDER . '/Src/Molajo/Filesystem/Type/Local.php');
*/
$loader->add('Molajo\\Filesystem\\',
    BASE_FOLDER . '/Src/');
$loader->add('Molajo\\Filesystem\\Adapter',
    BASE_FOLDER . '/Src/Molajo/Filesystem/Adapter');

$loader->add('Molajo\\Filesystem\\Exception',
    BASE_FOLDER . '/Src/Molajo/Filesystem/Exception');

$loader->add('Molajo\\Filesystem\\Type\\Local',
    BASE_FOLDER . '/Src/Molajo/Filesystem/Type');

$loader->add('Integration\\', 'Tests/');

$loader->register();
