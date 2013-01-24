<?php
/**
 * Filesystem Testing
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
if (defined ('MOLAJO')) {
} else {
    die;
}

echo 'BEGIN LOCAL FILESYSTEM TESTING', '<br />';
/**
echo 'Test 1 - Read ...';
$path =  ROOT_FOLDER . 'Filesystem/tests/test1.txt';
$options = array();
$class = 'Molajo\\Filesystem\\File';

$connection = new $class($path, $options);
$data    = $connection->read ($path);

if (trim($data) == 'text1') {
echo 'Success<br />';
} else {
echo 'Failed<br />';
die;
}
echo '<br />';

 */
/*
echo 'Test 2 - Write ...';
$path               = ROOT_FOLDER . 'Filesystem/tests/';
$file               = 'new.txt';
$data               = 'goes in here and more here.';
$replace            = false;
$options            = array();
$class              = 'Molajo\\Filesystem\\File';

$connection = new $class($path, $options);
$data       = $connection->write ($path, $file, $data, $replace);

if (trim ($data) == false) {
    echo 'Failed<br />';
} else {
    echo 'Success<br />';
    die;
}
echo '<br />';
*/
 /*
echo 'Test 2b - Write (overwrite)...';
$path               = ROOT_FOLDER . 'Filesystem/tests/';
$file               = 'test1.txt';
$data               = 'goes in here';
$replace            = true;
$options            = array();
$class              = 'Molajo\\Filesystem\\File';

$connection = new $class($path, $options);
$data       = $connection->write ($path, $file, $data, $replace);

if (trim ($data) == false) {
    echo 'Failed<br />';
} else {
    echo 'Success<br />';
    die;
}
echo '<br />';
  die;
  */
echo 'Test 3 - Delete file ';
$path               = ROOT_FOLDER . 'Filesystem/tests/test1.txt';
$delete_empty_directory            = true;
$options            = array();
$class              = 'Molajo\\Filesystem\\File';

$connection = new $class($path, $options);
$data       = $connection->delete
(
    $path,
    $delete_empty_directory
);

if ($data === true) {
    echo 'Success<br />';
} else {
    echo 'Failed<br />';
    die;
}
echo '<br />';


