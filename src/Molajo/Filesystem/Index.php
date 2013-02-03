<?php
namespace Molajo\Filesystem;

/**
 * Filesystem
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
include '../../../' . 'index.php';

use Molajo\Filesystem\Adapter as fsAdapter;
$read = BASE_FOLDER . '/Tests/Hold/test1.txt';
$read = BASE_FOLDER;
$adapter = new fsAdapter('Rename', $read);

echo $adapter->fs->data;

echo $adapter->fs->size;




