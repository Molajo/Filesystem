<?php
/**
 * Index.php
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
define('MOLAJO', 'This is a Molajo Distribution');

/**
 * Filesystem
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 *
 * Full interface specification:
 *  See https://github.com/Molajo/Filesystem/doc/speifications.md
 */

define('BASE_FOLDER', __DIR__);

/** Autoload, Namespaces and Overrides */
if (file_exists (BASE_FOLDER . '/Autoload.php')) {
} else {
    echo 'Autoload cannot be found.';
}
require_once BASE_FOLDER . '/Autoload.php';

