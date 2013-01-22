<?php
/**
 * Filesystem Testing
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
if (defined('MOLAJO')) {
} else {
    die;
}

echo 'BEGIN LOCAL FILESYSTEM TESTING' , '<br />';

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



echo 'Test 3 - Copy file ';
$path =  ROOT_FOLDER . 'Filesystem/tests/test1.txt';
$options = array();
$class = 'Molajo\\Filesystem\\File';
$target_filesystem = 'Local';
$target_directory = ROOT_FOLDER . 'Filesystem/tests';
$replace = false;
$create_directories = true;

$connection = new $class($path, $options);
$data    = $connection->copy
            ($path, $target_filesystem, $target_directory, $replace, $create_directories);

if (trim($data) == 'text1') {
    echo 'Success<br />';
} else {
    echo 'Failed<br />';
    die;
}
echo '<br />';


