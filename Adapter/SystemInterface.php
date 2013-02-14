<?php
/**
 * System Target Interface for Filesystem Adapter
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Filesystem\Adapter;

defined('MOLAJO') or die;

/**
 * System Target Interface for Filesystem Adapter
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @since     1.0
 */
interface SystemInterface
{
    /**
     * Set the FilesystemType
     *
     * @param  string  $filesystem_type
     *
     * @return  void
     * @since   1.0
     */
    public function setFilesystemType($filesystem_type);

    /**
     * Get the Filesystem Type
     *
     * @return  string
     * @since   1.0
     */
    public function getFilesystemType();

    /**
     * Set Root of Filesystem
     *
     * @return  void
     * @since   1.0
     */
    public function setRoot();

    /**
     * Get Root of Filesystem
     *
     * @return  string
     * @since   1.0
     */
    public function getRoot();

    /**
     * Set persistence indicator for Filesystem
     *
     * @return  bool
     * @since   1.0
     */
    public function setPersistence();

    /**
     * Get Persistence indicator
     *
     * @return  bool
     * @since   1.0
     */
    public function getPersistence();

    /**
     * Get Directory Permissions for Filesystem
     *
     * @return  void
     * @since   1.0
     */
    public function setDirectoryDefaultPermissions();

    /**
     * Get Directory Permissions for Filesystem
     *
     * @return  int
     * @since   1.0
     */
    public function getDirectoryDefaultPermissions();

    /**
     * Get File Permissions for Filesystem
     *
     * @return  void
     * @since   1.0
     */
    public function setFileDefaultPermissions();

    /**
     * Get File Permissions for Filesystem
     *
     * @return  int
     * @since   1.0
     */
    public function getFileDefaultPermissions();

    /**
     * Get Read Only for Filesystem
     *
     * @return  void
     * @since   1.0
     */
    public function setReadOnly();

    /**
     * Get Read Only for Filesystem
     *
     * @return  bool
     * @since   1.0
     */
    public function getReadOnly();

    /**
     * Set Filesystem Username for Logging On
     *
     * @return  void
     * @since   1.0
     */
    public function setUsername();

    /**
     * Get Filesystem Username for Logging On
     *
     * @return  string
     * @since   1.0
     */
    public function getUsername();

    /**
     * Set Filesystem Password for Logging On
     *
     * @return  void
     * @since   1.0
     */
    public function setPassword();

    /**
     * Get Filesystem Password for Logging On
     *
     * @return  string
     * @since   1.0
     */
    public function getPassword();

    /**
     * Set Filesystem Host
     *
     * @return  void
     * @since   1.0
     */
    public function setHost();

    /**
     * Get Filesystem Host
     *
     * @return  string
     * @since   1.0
     */
    public function getHost();

    /**
     * Set Host Port
     *
     * @return  void
     * @since   1.0
     */
    public function setPort();

    /**
     * Get Host Port
     *
     * @return  int
     * @since   1.0
     */
    public function getPort();

    /**
     * Set Filesystem Connection Type
     *
     * @return  void
     * @since   1.0
     */
    public function setConnectionType();

    /**
     * Get Filesystem Connection Type
     *
     * @return  string
     * @since   1.0
     */
    public function getConnectionType();

    /**
     * Set Filesystem Timeout
     *
     * @return  void
     * @since   1.0
     */
    public function setTimeout();

    /**
     * Get Filesystem Timeout
     *
     * @return  int
     * @since   1.0
     */
    public function getTimeout();

    /**
     * Set Filesystem PassiveMode
     *
     * @return  void
     * @since   1.0
     */
    public function setPassiveMode();

    /**
     * Get Filesystem PassiveMode
     *
     * @return  bool
     * @since   1.0
     */
    public function getPassiveMode();

    /**
     * Set Filesystem PassiveMode
     *
     * @return  void
     * @since   1.0
     */
    public function setInitialDirectory();

    /**
     * Get Filesystem PassiveMode
     *
     * @return  string
     * @since   1.0
     */
    public function getInitialDirectory();

    /**
     * Set Filesystem Connection
     *
     * @param   resource  $connection
     *
     * @return  void
     * @since   1.0
     */
    public function setConnection($connection);

    /**
     * Get Filesystem Connection
     *
     * @return  resource
     * @since   1.0
     */
    public function getConnection();

    /**
     * Set Filesystem Is Connected
     *
     * @return  void
     * @since   1.0
     */
    public function setIsConnected();

    /**
     * Get Filesystem Is Connected
     *
     * @return  bool
     * @since   1.0
     */
    public function getIsConnected();

    /**
     * Set Filesystem Timezone
     *
     * @return  void
     * @since   1.0
     */
    public function setTimezone();

    /**
     * Get Filesystem Timezone
     *
     * @return  string
     * @since   1.0
     */
    public function getTimezone();
}
