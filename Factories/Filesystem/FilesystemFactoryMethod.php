<?php
/**
 * Filesystem Factory Method
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 */
namespace Molajo\Factories\Filesystem;

use Exception;
use CommonApi\Exception\RuntimeException;
use CommonApi\IoC\FactoryInterface;
use CommonApi\IoC\FactoryBatchInterface;
use Molajo\IoC\FactoryMethodBase;

/**
 * Filesystem Factory Method
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @since      1.0
 */
class FilesystemFactoryMethod extends FactoryMethodBase implements FactoryInterface, FactoryBatchInterface
{
    /**
     * Adapter Instance
     *
     * @var     object
     * @since   1.0
     */
    public $adapter;

    /**
     * Adapter Adapter Instance
     *
     * @var     object
     * @since   1.0
     */
    public $adapter_adapter;

    /**
     * Constructor
     *
     * @param  $options
     *
     * @since  1.0
     */
    public function __construct(array $options = array())
    {
        $options['product_name']             = basename(__DIR__);
        $options['store_instance_indicator'] = true;
        $options['product_namespace']        = 'Molajo\\Filesystem\\Driver';

        parent::__construct($options);
    }

    /**
     * Instantiate Class
     *
     * @return  $this
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException;
     */
    public function instantiateClass()
    {
        $options              = array();
        $adapter              = $this->getAdapterAdapter($options);
        $this->product_result = $this->getAdapter($adapter);

        return $this;
    }

    /**
     * Get the Filesystem specific Adapter Adapter
     *
     * @param   string $options
     *
     * @return  object
     * @since   1.0
     * @throws  FactoryInterface
     */
    protected function getAdapterAdapter($options = array())
    {
        $class = 'Molajo\\Filesystem\\Adapter\\Local';

        try {
            return new $class($options);
        } catch (Exception $e) {
            throw new RuntimeException
            ('Filesystem: Could not instantiate Filesystem Adapter Adapter: Local');
        }
    }

    /**
     * Get Filesystem Adapter, inject with specific Filesystem Adapter Adapter
     *
     * @param   object $adapter
     *
     * @return  object
     * @since   1.0
     * @throws  FactoryInterface
     */
    protected function getAdapter($adapter)
    {
        $class = $this->product_namespace;

        try {
            return new $class($adapter);
        } catch (Exception $e) {

            throw new RuntimeException
            ('Filesystem: Could not instantiate Adapter for Filesystem Type: Local');
        }
    }

    /**
     * Quick file tests
     *
     * @return  $this
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException;
     */
    protected function testAPI()
    {
        /** 1. Exists */
        $true_or_false = $this->product_result->exists(__DIR__ . '/Configuration.xml');

        if ($true_or_false === false) {
            echo 'LocalFilesystem Exists did not work when tested in Filesystem Factory Method'
                . __DIR__ . '/Configuration.xml. <br /> ';
            die;
        } else {
            echo 'LocalFilesystem Exists when tested in Filesystem Factory Method'
                . __DIR__ . '/Configuration.xml. <br /> ';
        }

        /** Metadata */
        $metadata = $this->product_result->getMetadata(__DIR__ . '/Configuration.xml');

        /** List */
        $path            = $this->options['base_path'] . '/Vendor' . '/Molajo';
        $recursive       = true;
        $extension_list  = null;
        $include_files   = true;
        $include_folders = false;
        $filename_mask   = null;

        $list_of_results = $this->product_result->getList(
            $path,
            $recursive,
            $extension_list,
            $include_files,
            $include_folders,
            $filename_mask
        );

        /** Write */
        $path     = __DIR__ . '/Testfile.txt';
        $data     = 'Here is stuff to read.';
        $replace  = true;
        $append   = false;
        $truncate = false;

        $this->product_result->write($path, $data, $replace, $append, $truncate);

        /** Read */
        $contents = $this->product_result->read($path);
        echo $contents;

        $target_directory       = __DIR__;
        $target_name            = 'Newfile.txt';
        $replace                = true;
        $target_adapter_adapter = 'Local';

        $this->product_result->copy($path, $target_directory, $target_name, $replace, $target_adapter_adapter);

        die;

        $this->product_result->move($path, $target_directory, $target_name, $replace, $target_adapter_adapter);

        $this->product_result->rename($path, $new_name);

        $this->product_result->delete($path, $recursive);

        $this->product_result->changeOwner($path, $user_name, $recursive);

        $this->product_result->changeGroup($path, $group_id, $recursive);

        $this->product_result->changePermission($path, $permission, $recursive);

        $this->product_result->touch($path, $modification_time, $access_time, $recursive);

        die;
    }
}
