<?php
/**
 * Filesystem
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
define('MOLAJO', 'This is a Molajo Distribution');

if (substr($_SERVER['DOCUMENT_ROOT'], - 1) == '/') {
    define('ROOT_FOLDER', $_SERVER['DOCUMENT_ROOT']);
} else {
    define('ROOT_FOLDER', $_SERVER['DOCUMENT_ROOT'] . '/');
}

define('BASE_FOLDER', __DIR__);

include BASE_FOLDER . '/' . 'ClassLoader.php';

$loader = new ClassLoader();

$loader->add('Molajo', BASE_FOLDER . '/src/');
$loader->add('Integration', BASE_FOLDER . '/tests/');

$loader->register();

spl_autoload_register(function($class) {
        if (0 === (strpos($class, 'Molajo\\'))) {
            $path = __DIR__.'/../'.implode('/', array_slice(explode('\\', $class), 3)).'.php';

            if (!stream_resolve_include_path($path)) {
                return false;
            }
            require_once $path;
            return true;
        }
    });
