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
$read = BASE_FOLDER . '/Tests/Hold/testreally-is-not-there.txt';
$adapter = new fsAdapter('Read', $read);

echo '<pre>';
echo var_dump($adapter->fs);





