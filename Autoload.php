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

$x->add ('Molajo\\Filesystem\\Driver', BASE_FOLDER . 'src/Molajo/Filesystem/FilesystemException.php');

$x->add ('Molajo\\Filesystem\\Api\\Adapter', BASE_FOLDER . 'src/Molajo/Filesystem/Api/Adapter.php');
//$x->add ('Molajo\\Filesystem\\Api\\Path', BASE_FOLDER . 'src/Molajo/Filesystem/Api/Path.php');
//$x->add ('Molajo\\Filesystem\\Api\\Directory', BASE_FOLDER . 'src/Molajo/Filesystem/Api/Directory.php');
////$x->add ('Molajo\\Filesystem\\Api\\File', BASE_FOLDER . 'src/Molajo/Filesystem/Api/File.php');

$x->add ('Molajo\\Filesystem\\Adapter\\Adapter', BASE_FOLDER . 'src/Molajo/Filesystem/Adapter/Adapter.php'); // 1
$x->add ('Molajo\\Filesystem\\Adapter\\Ftp', BASE_FOLDER . 'src/Molajo/Filesystem/Adapter/Ftp.php');
//$x->add ('Molajo\\Filesystem\\Adapter\\Github', BASE_FOLDER . 'src/Molajo/Filesystem/Adapter/Github.php');
//$x->add ('Molajo\\Filesystem\\Adapter\\Ldap', BASE_FOLDER . 'src/Molajo/Filesystem/Adapter/Ldap.php');
$x->add ('Molajo\\Filesystem\\Adapter\\Local', BASE_FOLDER . 'src/Molajo/Filesystem/Adapter/Local.php');
//$x->add ('Molajo\\Filesystem\\Adapter\\Media', BASE_FOLDER . 'src/Molajo/Filesystem/Adapter/Media.php');
//$x->add ('Molajo\\Filesystem\\Adapter\\Registry', BASE_FOLDER . 'src/Molajo/Filesystem/Adapter/Registry.php');
//$x->add ('Molajo\\Filesystem\\Stream', BASE_FOLDER . 'src/Molajo/Filesystem/Stream.php');
// cURL and Http

//$x->add ('Molajo\\Filesystem\\Access\\Path', BASE_FOLDER . 'src/Molajo/Filesystem/Access/Path.php');
//$x->add ('Molajo\\Filesystem\\Access\\Directory', BASE_FOLDER . 'src/Molajo/Filesystem/Access/Directory.php');
//$x->add ('Molajo\\Filesystem\\Access\\File', BASE_FOLDER . 'src/Molajo/Filesystem/Access/File.php');

include BASE_FOLDER . '/Test.php';
