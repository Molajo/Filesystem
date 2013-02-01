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

/** Load Classloader */
include BASE_FOLDER . '/' . 'ClassLoader.php';

$loader = new ClassLoader();
$loader->add('Molajo\Filesystem', BASE_FOLDER . '/src/Molajo');
$loader->add('Molajo\Filesystem\Adapters', BASE_FOLDER . '/src/Molajo/Filesystem');
$loader->add('Tests\Integration', BASE_FOLDER . '/Tests/Filesystem');
$loader->register();

/** Load Classloader */
include BASE_FOLDER . '/Tests/Filesystem/AdapterTest.php';
$adapterTest = new Tests\Integration\AdapterTest();
$test        = $adapterTest->testSetAdapter();
