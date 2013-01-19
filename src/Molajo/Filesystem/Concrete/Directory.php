<?php
/**
 * Directory Instance for Filesystem
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem\Concrete;

defined ('MOLAJO') or die;

use Molajo\Filesystem\Directory as DirectoryInterface;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * Directory Instance for Filesystem
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
Class Directory extends Path implements DirectoryInterface
{
    /**
     * Adapter Instance
     *
     * @var    object  Adapter
     * @since  1.0
     */
    protected $adapter;

    /**
     * Construct
     *
     * @param   Adapter     $adapter
     * @param   Filesystem  $filesystem
     * @param   array       $options
     *
     * @since   1.0
     */
    public function __construct (Adapter $adapter, $options = array())
    {
        $this->adapter    = $adapter;
        $this->filesystem = $filesystem;
        $this->options    = $options;

        return;
    }

    /**
     * Retrieves the name of the file specified in the path
     *
     * @param   string  $path
     *
     * @return  null
     * @since   1.0
     */
    public function getName ($path)
    {

    }

    /**
     * Returns a list of files located at path directory
     *
     * @param   string  $path
     * @param   string  $file_name_mask
     * @param   string  $extension
     * @param   bool    $recursive
     *
     * @return  null
     * @since   1.0
     */
    public function getList ($path, $file_name_mask = '', $extension = '', $recursive = true)
    {

    }

    /**
     * Create the folder identified in name for the parent identified in path.
     *
     * @param   string  $path
     * @param   string  $name
     * @param   string  $permission
     *
     * @return  null
     * @since   1.0
     */
    public function create ($path, $name, $permission)
    {

    }
    /**
     * Recursively remove a directory
     *
     * Uses the process component if proc_open is enabled on the PHP
     * installation.
     *
     * @param string $directory
     * @return bool
     */
    public function delete($path)
    {
        if (is_dir($path)) {
        } else {
            return true;
        }

        if (!function_exists('proc_open')) {
            return $this->removeDirectoryPhp($directory);
        }

        if (defined('PHP_WINDOWS_VERSION_BUILD')) {
            $cmd = sprintf('rmdir /S /Q %s', escapeshellarg(realpath($directory)));
        } else {
            $cmd = sprintf('rm -rf %s', escapeshellarg($directory));
        }

        $result = $this->getProcess()->execute($cmd) === 0;

        // clear stat cache because external processes aren't tracked by the php stat cache
        clearstatcache();

        return $result && !is_dir($directory);
    }

    function copyFiles()

    {

        if (!isset($_SERVER['DEPLOYMENT_TARGET'])) {

            echo "Cannot find pyhsical path to application root.\n";

            echo "There should be an 'DEPLOYMENT_TARGET' env variable.\n";

            exit(1);

        }



        echo "Copying code to webroot\n";

        copyDirectory($_SERVER['DEPLOYMENT_SOURCE'], $_SERVER['DEPLOYMENT_TARGET']);

    }



    function copyDirectory($source, $target)

    {

        $it = new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS);

        $ri = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::SELF_FIRST);



        if ( !file_exists($target)) {

            mkdir($target, 0777, true);

        }



        foreach ($ri as $file) {

            $targetPath = $target . DIRECTORY_SEPARATOR . $ri->getSubPathName();

            if ($file->isDir()) {

                if ( ! file_exists($targetPath)) {

                    mkdir($targetPath);

                }

            } else if (!file_exists($targetPath) || filemtime($targetPath) < filemtime($file->getPathname())) {

                copy($file->getPathname(), $targetPath);

            }

        }

    }
}
