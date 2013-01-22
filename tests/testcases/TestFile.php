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

echo 'BEGIN Testing File Class' , '<br />';

echo 'SKIPPING Test 1 - Does not exists (should fail) ...';
$path =  ROOT_FOLDER . 'Filesystem/tests/XXXXXXXtest1.txt';
$options = array();
$class = 'Molajo\\Filesystem\\File';
//$connection = new $class($path, $options);
//$data    = $connection->read ();

//if (trim($data) == 'text1') {
//    echo 'Complete, successful';
//} else {
//    echo 'Complete, failed';
//    die;
//}
echo '<br />';


echo 'Test 2 - read a file begins ...';
$path =  ROOT_FOLDER . 'Filesystem/tests/test1.txt';
$options = array();
$class = 'Molajo\\Filesystem\\File';
$connection = new $class($path, $options);
$data    = $connection->read ();

if (trim($data) == 'text1') {
    echo 'Complete, successful<br />';
} else {
    echo 'Complete, failed<br />';
    die;
}
echo '<br />';

echo 'END Testing File Class' , '<br />';
