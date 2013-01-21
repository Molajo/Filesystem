<?php
/**
 * Test
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */

defined ('MOLAJO') or die;
/**
$ftp_adapter_options = array(
    'adapter_name'   => 'FTP Server',
    'username'       => 'integrationFTP',
    'password'       => 'mATs7799',
    'host'           => 'secureftp.nebraska.edu',
    'port'           => 21,
    'root'           => '/integrationFTP',
    'timeout'        => 15,
    'passive_mode'   => true
);

$adapter = 'Local';
*/
include BASE_FOLDER . '/src/Molajo/Filesystem/Driver.php';
include BASE_FOLDER . '/src/Molajo/Filesystem/Api/Adapter.php';                          // Interface
include BASE_FOLDER . '/src/Molajo/Filesystem/Adapter/Adapter.php';
include BASE_FOLDER . '/src/Molajo/Filesystem/Adapter/Local.php';

$local_adapter_options = array(
    'adapter_name'   => 'Local Filesystem',
    'username'       => '',
    'password'       => '',
    'host'           => '',
    'port'           => '',
    'root'           => BASE_FOLDER,
    'timeout'        => 15,
    'passive_mode'     => true
);

$class = 'Molajo\\Filesystem\\Adapter\\Local';
$adapterServices = new $class($local_adapter_options);


$file_options = array('xyz' => BASE_FOLDER);
$path = BASE_FOLDER . '/src/Molajo/' . 'Media';

$fileServices = new File($adapter, $path, $file_options);








if ($filesystem->has ('foo')) {
    $filesystem->write ('foo', 'Some content');
}

echo $filesystem->read ('foo');
