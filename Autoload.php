<?php
/**
 * Filesystem Autoload Class
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */

if (defined ('MOLAJO')) {
} else {
    die;
}

/** Class Loader */
include BASE_FOLDER . '/' . 'ClassLoader.php';
$x = new ClassLoader();

$x->add ('Molajo\\Filesystem\\FilesystemException', BASE_FOLDER . '/Molajo/Filesystem/FilesystemException.php');

$x->add ('Molajo\\Filesystem\\Adapter', BASE_FOLDER . '/Molajo/Filesystem/Adapter.php');
$x->add ('Molajo\\Filesystem\\Filesystem', BASE_FOLDER . '/Molajo/Filesystem/Filesystem.php');
$x->add ('Molajo\\Filesystem\\Entry', BASE_FOLDER . '/Molajo/Filesystem/Entry.php');
$x->add ('Molajo\\Filesystem\\Directory', BASE_FOLDER . '/Molajo/Filesystem/Directory.php');
$x->add ('Molajo\\Filesystem\\File', BASE_FOLDER . '/Molajo/Filesystem/File.php');

$x->add ('Molajo\\Filesystem\\Adapter\\Adapter', BASE_FOLDER . '/Molajo/Filesystem/Adapter/Adapter.php');
$x->add ('Molajo\\Filesystem\\Adapter\\Ftp', BASE_FOLDER . '/Molajo/Filesystem/Adapter/Ftp.php');
$x->add ('Molajo\\Filesystem\\Adapter\\Github', BASE_FOLDER . '/Molajo/Filesystem/Adapter/Github.php');
$x->add ('Molajo\\Filesystem\\Adapter\\Ldap', BASE_FOLDER . '/Molajo/Filesystem/Adapter/Ldap.php');
$x->add ('Molajo\\Filesystem\\Adapter\\Local', BASE_FOLDER . '/Molajo/Filesystem/Adapter/Local.php');
$x->add ('Molajo\\Filesystem\\Adapter\\Media', BASE_FOLDER . '/Molajo/Filesystem/Adapter/Media.php');
$x->add ('Molajo\\Filesystem\\Adapter\\Registry', BASE_FOLDER . '/Molajo/Filesystem/Adapter/Registry.php');
//$x->add ('Molajo\\Filesystem\\Stream', BASE_FOLDER . '/Molajo/Filesystem/Stream.php');
// cURL and Http

$x->add ('Molajo\\Filesystem\\Concrete\\Filesystem', BASE_FOLDER . '/Molajo/Filesystem/Concrete/Filesystem.php');
$x->add ('Molajo\\Filesystem\\Concrete\\Entry', BASE_FOLDER . '/Molajo/Filesystem/Concrete/Entry.php');
$x->add ('Molajo\\Filesystem\\Concrete\\Directory', BASE_FOLDER . '/Molajo/Filesystem/Concrete/Directory.php');
$x->add ('Molajo\\Filesystem\\Concrete\\File', BASE_FOLDER . '/Molajo/Filesystem/Concrete/File.php');

include BASE_FOLDER . '/Test.php';
