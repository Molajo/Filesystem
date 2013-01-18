<?php
/**
 * Test
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */

defined ('MOLAJO') or die;
echo file_get_contents("http://www.example.com");


$options = array(
    'root'           => BASE_FOLDER,
    'username'       => 'integrationFTP',
    'password'       => 'mATs7799',
    'host'           => 'secureftp.nebraska.edu',
    'port'           => 21,
    'root'           => '/integrationFTP',
    'timeout'        => 15,
    'is_passive'     => true,
    'name'           => 'Filesystem name'
);

$path = BASE_FOLDER . '/Molajo/' . 'Media';

include BASE_FOLDER . '/Molajo/Filesystem/Adapter.php';
include BASE_FOLDER . '/Molajo/Filesystem/Adapter/Adapter.php';
include BASE_FOLDER . '/Molajo/Filesystem/Adapter/Ftp.php';

$class = 'Molajo\\Filesystem\\Adapter\\Ftp';
$adapter = new $class($options);

echo '<pre>';
var_dump ($adapter);
die;
$filesystem = new File($adapter, $options);

die;
if (! $filesystem->has ('foo')) {
    $filesystem->write ('foo', 'Some content');
}

echo $filesystem->read ('foo');


$adapter    = new Molajo\Filesystem\Adapter\Local('/Molajo/Media');
$filesystem = new Molajo\Filesystem\Directory($adapter);

echo '<pre>';
var_dump ($filesystem);
echo '</pre>';

die;
if ($filesystem->has ('foo')) {
    $filesystem->write ('foo', 'Some content');
}

echo $filesystem->read ('foo');
