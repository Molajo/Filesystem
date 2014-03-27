<?php
/**
 * Local Filesystem Adapter
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Filesystem\Adapter;

use stdClass;
use Exception;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use CommonApi\Exception\RuntimeException;
use CommonApi\Filesystem\FilesystemInterface;
use CommonApi\Filesystem\ConnectionInterface;

/**
 * Local Filesystem Adapter
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @since      1.0
 */
class Local extends AbstractAdapter implements ConnectionInterface, FilesystemInterface
{
    /**
     * Filesystem Adapter
     *
     * @var    string
     * @since  1.0
     */
    protected $adapter = 'Local';

    /**
     * Constructor
     *
     * @param   array $options
     *
     * @since   1.0
     */
    public function __construct(array $options = array())
    {
        $this->connect($options);
    }

    /**
     * Adapter Interface Step 1:
     *
     * Method to connect to a Local server
     *
     * @param    array $options
     *
     * @return   $this
     * @since    1.0
     * @throws   RuntimeException
     */
    public function connect($options = array())
    {
        if (is_array($options)) {
        } else {
            $options = array();
        }

        $this->options = $options;

        $this->setTimezone();
        $this->setInitialDirectory();
        $this->setRoot();
        $this->setDefaultDirectoryPermissions();
        $this->setDefaultFilePermissions();
        $this->setReadOnly();

        return $this;
    }

    /**
     * Close the Connection
     *
     * @return  $this
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public function close()
    {
        if ($this->is_connected === true) {
            try {
                ftp_close($this->connection);
            } catch (Exception $e) {

                throw new RuntimeException
                ('Filesystem Adapter Ftp: Closing Ftp Connection Failed');
            }
        }

        return $this;
    }

    /**
     * Determines if file or folder identified in path exists
     *
     * @param   string $path
     *
     * @return  bool
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public function exists($path)
    {
        if ($path == '') {
            throw new RuntimeException
            ('Local Filesystem Exists: Required Path was not provided.');
        }

        $this->setPath($path);

        $this->setExists();

        return $this->getExists();
    }

    /**
     * Returns an associative array of metadata for the file or folder specified in path
     *
     * Note: this list can vary for filesystem
     *
     * @param   string $path
     *
     * @return  object
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public function getMetadata($path)
    {
        if ($path == '') {
            throw new RuntimeException
            ('Local Filesystem Exists: Required Path was not provided.');
        }

        $this->setPath($path);

        $metadata = new stdClass();

        $metadata->adapter                       = $this->getAdapterAdapter();
        $metadata->path                          = $this->getPath();
        $metadata->initial_directory             = $this->getInitialDirectory();
        $metadata->root                          = $this->getRoot();
        $metadata->host                          = $this->getHost();
        $metadata->default_directory_permissions = $this->getDefaultDirectoryPermissions();
        $metadata->default_file_permissions      = $this->getDefaultFilePermissions();
        $metadata->read_only                     = $this->getReadOnly();
        $metadata->exists                        = $this->setExists()->getExists();

        if ($metadata->exists === true) {
        } else {
            throw new RuntimeException
            ('Local Filesystem Adapter: Cannot retrieve Metadata for File/Path that does not exist: '
            . $this->path);
        }

        $metadata->absolute_path          = $this->setAbsolutePath()->getAbsolutePath();
        $metadata->is_absolute_path       = $this->setIsAbsolutePath()->getIsAbsolutePath();
        $metadata->is_root                = $this->setIsRoot()->getIsRoot();
        $metadata->is_directory           = $this->setIsDirectory()->getIsDirectory();
        $metadata->is_file                = $this->setIsFile()->getIsFile();
        $metadata->is_link                = $this->setIsLink()->getIsLink();
        $metadata->type                   = $this->setType()->getType();
        $metadata->name                   = $this->setName()->getName();
        $metadata->parent                 = $this->setParent()->getParent();
        $metadata->extension              = $this->setExtension()->getExtension();
        $metadata->name_without_extension = $this->setNameNoExtension()->getNameNoExtension();
        $metadata->mimetype               = $this->setMimeType()->getMimeType();
        $metadata->owner                  = $this->setOwner()->getOwner();
        $metadata->group                  = $this->setGroup()->getGroup();
        $metadata->create_date            = $this->setCreateDate()->getCreateDate();
        $metadata->access_date            = $this->setAccessDate()->getAccessDate();
        $metadata->modified_date          = $this->setModifiedDate()->getModifiedDate();
        $metadata->is_readable            = $this->setIsReadable()->getIsReadable();
        $metadata->is_writeable           = $this->setIsWriteable()->getIsWriteable();
        $metadata->is_executable          = $this->setIsExecutable()->getIsExecutable();
        $metadata->hash_file_md5          = $this->setHashFileMd5()->getHashFileMd5();
        $metadata->hash_file_sha1         = $this->setHashFileSha1()->getHashFileSha1();
        $metadata->hash_file_sha1_20      = $this->setHashFileSha1_20()->getHashFileSha1_20();

        if ($this->exists === true) {
            $this->discovery($this->path);
        }

        $metadata->size = $this->setSize()->getSize();

        return $metadata;
    }

    /**
     * Returns the contents of the file identified in path
     *
     * @param   string $path
     *
     * @return  null|string
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public function read($path)
    {
        if ($path == '') {
            throw new RuntimeException
            ('Filesystem Path is required, but was not provided.');
        }

        $this->setPath($path);

        if ($this->setExists()->getExists() === true) {
        } else {
            throw new RuntimeException
            (ucfirst(strtolower($this->getAdapterAdapter())) . ' Filesystem Read: File does not exist: ' . $this->path);
        }

        if ($this->setIsFile()->getIsFile() === true) {
        } else {
            throw new RuntimeException
            (ucfirst(strtolower($this->getAdapterAdapter())) . ' Filesystem Read: Is not a file: ' . $this->path);
        }

        if ($this->setIsReadable()->getIsReadable() === true) {
        } else {

            throw new RuntimeException
            (ucfirst(
                strtolower($this->getAdapterAdapter())
            ) . ' Filesystem Read: Not permitted to read: ' . $this->path);
        }

        try {
            $this->data = file_get_contents($this->path);
        } catch (Exception $e) {

            throw new RuntimeException
            (ucfirst(strtolower($this->getAdapterAdapter())) . ' Filesystem Read: Error reading file: ' . $this->path);
        }

        if ($this->data === false) {
            (ucfirst(strtolower($this->getAdapterAdapter())) . ' Filesystem Read: Empty File: ' . $this->path);
        }

        return $this->data;
    }

    /**
     * Returns a list of file and folder names located at path directory, optionally recursively,
     *  optionally filtered by a list of file extension values, filename mask, and inclusion or exclusion
     *  of files and/or folders
     *
     * @param   string $path
     * @param   bool   $recursive
     * @param   string $extension_list
     * @param   bool   $include_files
     * @param   bool   $include_folders
     * @param   null   $filename_mask
     *
     * @return  mixed
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     * @api
     */
    public function getList(
        $path,
        $recursive = false,
        $extension_list = '',
        $include_files = false,
        $include_folders = false,
        $filename_mask = null
    ) {
        if ($path == '') {
            throw new RuntimeException
            ('Filesystem Path is required for getList, but was not provided.');
        }

        $this->setPath($path);

        if ($this->setExists()->getExists() === true) {
        } else {
            throw new RuntimeException
            ('Local Filesystem getList: Path does not exist: ' . $this->path);
        }

        if ($this->setIsDirectory()->getIsDirectory() === true) {
        } else {
            throw new RuntimeException
            ('Local Filesystem getList: Path is not a directory: ' . $this->path);
        }

        if ($this->setIsReadable()->getIsReadable()) {
        } else {
            throw new RuntimeException
            ('Local Filesystem getList: Path is not a readable: ' . $this->path);
        }

        $this->discovery();

        $extension_array = explode(',', $extension_list);

        if (count($extension_array) == 1 && $extension_array[0] == '') {
            $extension_array = array();
        }

        if ($filename_mask == '') {
            $filename_mask = null;
        }

        $files = array();
        if ($include_folders === false) {
        } else {
            if (count($this->directories) > 0 && is_array($this->directories)) {
                foreach ($this->directories as $directory) {

                    if ($recursive === false) {
                        if ($this->path == $directory) {
                            $files[] = $directory;
                        }
                    } else {
                        $files[] = $directory;
                    }
                }
            }
        }

        if ($include_files === false) {
        } else {

            if (count($this->files) > 0 && is_array($this->files)) {

                foreach ($this->files as $file) {

                    $selected = '';

                    if ($recursive === false) {
                        if ($this->path == pathinfo($file, PATHINFO_DIRNAME)) {
                            $selected = $file;
                        }
                    } else {
                        $selected = $file;
                    }

                    /** Extension */
                    if ($selected == '') {
                    } else {
                        if (count($extension_array) > 0) {
                            $test = pathinfo($selected, PATHINFO_EXTENSION);

                            if (in_array($test, $extension_array)) {
                            } else {
                                $selected = '';
                            }
                        }
                    }

                    /** Filename Mask */
                    if ($selected == '') {
                    } else {
                        if ($filename_mask == null) {
                        } else {

                            if (strpos($selected, $filename_mask)) {
                            } else {
                                $selected = '';
                            }
                        }
                    }

                    /** Use if Passed all tests */
                    if ($selected == '') {
                    } else {
                        $files[] = $selected;
                    }
                }
            }
        }

        if (count($files) > 0) {
            asort($files);
        }

        $this->data = $files;

        return $this->data;
    }

    /**
     * For a file request, creates, appends to, replaces or truncates the file identified in path
     * For a folder request, create is the only valid option
     *
     * @param   string $path
     * @param   string $data     (file, only)
     * @param   bool   $replace  (file, only)
     * @param   bool   $append   (file, only)
     * @param   bool   $truncate (file, only)
     *
     * @return  $this
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public function write($path = '', $data = '', $replace = false, $append = false, $truncate = false)
    {
        if ($path == '') {
            throw new RuntimeException
            ('Filesystem Path is required for Write, but was not provided.');
        }

        $this->setPath($path);

        if ($this->setExists()->getExists() === true
            && $replace === false
        ) {
            throw new RuntimeException
            ('Local Filesystem Write: File exists and replace is false: ' . $this->path);
        }

        $this->setIsDirectory();
        $this->setIsFile();

        $this->setParent();
        $this->setName();
        $this->setExtension();
        $this->setIsWriteable();

        $this->discovery();

        if ($append === true) {
            $this->append($data);

            return $this;
        }

        if ($truncate === true) {
            $this->truncate();

            return $this;
        }

        if ($this->exists === false) {
        } elseif ($this->is_file === true || $this->is_directory === true) {
        } else {
            throw new RuntimeException
            (ucfirst(
                strtolower($this->getAdapterAdapter())
            ) . ' Filesystem Write: must be directory or file: ' . $this->path);
        }

        if (trim($data) == '' || strlen($data) == 0) {
            if ($this->is_file === true) {

                throw new RuntimeException
                (ucfirst(strtolower($this->getAdapterAdapter()))
                . ' Filesystem:  attempting to write no data to file: ' . $this->parent . '/' . $this->name);
            }
        }

        if (trim($data) == '' || strlen($data) == 0) {
            if ($this->name == '') {
                throw new RuntimeException
                (ucfirst(strtolower($this->getAdapterAdapter()))
                . ' Filesystem:  attempting to write no data to file: ' . $this->parent . '/' . $this->name);
            } else {
                $this->createDirectory($this->parent . '/' . $this->name);

                return $this;
            }
        }

        if (file_exists($this->path)) {
        } else {
            if (is_dir($this->parent)) {
                $this->createDirectory($this->parent);
            }
        }

        if ($this->getIsWriteable() === false) {
            throw new RuntimeException
            (ucfirst(strtolower($this->getAdapterAdapter()))
            . ' Filesystem:  file is not writable: ' . $this->parent . '/' . $this->name);
        }

        if (file_exists($this->path) === true) {

            if ($replace === false) {
                throw new RuntimeException
                (ucfirst(strtolower($this->getAdapterAdapter()))
                . ' Filesystem:  attempting to write to existing file: ' . $this->parent . '/' . $this->name);
            }

            \unlink($this->parent . '/' . $this->name);
        }

        try {
            \file_put_contents($this->parent . '/' . $this->name, $data);
        } catch (Exception $e) {

            throw new RuntimeException
            (ucfirst(strtolower($this->getAdapterAdapter()))
            . ' Filesystem:  error writing file ' . $this->parent . '/' . $this->name);
        }

        return $this;
    }

    /**
     * Append data to file identified in path using the data value
     *
     * @param   string $data
     *
     * @return  $this
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     * @throws  \CommonApi\Exception\RuntimeException
     */
    private function append($data)
    {
        if ($this->exists === true) {
        } elseif ($this->is_file === false) {
        } else {
            throw new RuntimeException
            (ucfirst(strtolower($this->getAdapterAdapter()))
            . ' Filesystem:  attempting to append to a folder, not a file ' . $this->path);
        }

        try {
            \file_put_contents($this->path, $data, FILE_APPEND);
        } catch (Exception $e) {

            throw new RuntimeException
            (ucfirst(strtolower($this->getAdapterAdapter())) . ' Filesystem:  error appending to file '
            . $this->path);
        }

        return $this;
    }

    /**
     * Truncate file identified in path using the data value
     *
     * @return  $this
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    private function truncate()
    {
        if ($this->exists === false) {
            throw new RuntimeException
            (ucfirst(strtolower($this->getAdapterAdapter()))
            . ' Filesystem:  attempting to truncate file that does not exist. '
            . $this->path);
        }

        if ($this->is_file === true) {
        } else {
            throw new RuntimeException
            (ucfirst(strtolower($this->getAdapterAdapter()))
            . ' Filesystem:  only a file can be truncated. ' . $this->path);
        }

        if ($this->setIsWriteable($this->path) === false) {
            throw new RuntimeException
            (ucfirst(strtolower($this->getAdapterAdapter()))
            . ' Filesystem:  file is not writable and cannot be truncated: '
            . $this->path);
        }

        try {
            $fp = \fopen($this->path, "w");
            fclose($fp);
        } catch (Exception $e) {

            throw new RuntimeException
            (ucfirst(strtolower($this->getAdapterAdapter())) . ' Filesystem:  error truncating file ' . $this->path);
        }

        return;
    }

    /**
     * Deletes the file or folder identified in path, optionally deletes recursively
     *
     * @param   string $path
     * @param   bool   $recursive
     *
     * @return  $this
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     * @api
     */
    public function delete($path, $recursive = false)
    {
        if ($path == '') {
            throw new RuntimeException
            ('Filesystem Path is required, but was not provided.');
        }

        if ($this->is_root === true) {
            return $this;
        }

        $options                          = array();
        $options['delete_subdirectories'] = $delete_subdirectories;

        $this->setPath($path);
        $this->getMetadata();

        if ($this->is_root === true) {
            throw new RuntimeException
            (ucfirst(
                strtolower($this->getAdapterAdapter())
            ) . ' Filesystem Delete: Request to delete root is not allowed'
            . $this->path);
        }

        if (file_exists($this->path)) {
        } else {

            throw new RuntimeException
            (ucfirst(strtolower($this->getAdapterAdapter())) . ' Filesystem Delete: File not found ' . $this->path);
        }

        if ($this->is_writable === false) {

            throw new RuntimeException
            (ucfirst(strtolower($this->getAdapterAdapter())) . ' Filesystem Delete: No write access to file/path: '
            . $this->path);
        }

        try {

            if (file_exists($this->path)) {
            } else {
                return;
            }

            $this->_deleteDiscoveryFilesArray();
            $this->_deleteDiscoveryDirectoriesArray();
        } catch (Exception $e) {

            throw new RuntimeException
            (ucfirst(strtolower($this->getAdapterAdapter())) . ' Filesystem Delete: failed for: ' . $this->path);
        }

        return;
    }

    /**
     * Copies the file/folder in $path to the target_directory (optionally target_name),
     *  replacing content, if indicated. Can copy to target_adapter.
     *
     * @param   string $path
     * @param   string $target_directory
     * @param   string $target_name
     * @param   bool   $replace
     * @param   string $target_adapter
     *
     * @return  $this
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     * @api
     */
    public function copy(
        $path,
        $target_directory,
        $target_name = '',
        $replace = true,
        $target_adapter = ''
    ) {
        if ($path == '') {
            throw new RuntimeException
            ('Filesystem Path is required, but was not provided.');
        }

        $options                     = array();
        $options['target_directory'] = $target_directory;
        $options['target_name']      = $target_name;
        $options['replace']          = $replace;
        $options['target_adapter']   = $target_adapter;

        $this->setPath($path);
        $this->getMetadata();
    }

    /**
     * Moves the file/folder in $path to the target_directory (optionally target_name),
     *  replacing content, if indicated. Can move to target_adapter.
     *
     * @param   string $path
     * @param   string $target_directory
     * @param   string $target_name
     * @param   bool   $replace
     * @param   string $target_adapter
     *
     * @return  $this
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     * @api
     */
    public function move(
        $path,
        $target_directory,
        $target_name = '',
        $replace = true,
        $target_adapter = ''
    ) {
        if ($path == '') {
            throw new RuntimeException
            ('Filesystem Path is required, but was not provided.');
        }

        $options                     = array();
        $options['target_directory'] = $target_directory;
        $options['target_name']      = $target_name;
        $options['replace']          = $replace;
        $options['target_adapter']   = $target_adapter;

        $this->setPath($path);
        $this->getMetadata();

        if ($target_adapter == '') {
            $target_adapter = $this->getAdapterAdapter();
        }

        $this->moveOrCopy($target_directory, $target_name, $replace, $target_adapter, 'copy');

        return $this;
    }

    /**
     * Copies the file identified in $path to the $target_directory for the $target_adapter adapter
     *  replacing existing contents and creating directories needed, if indicated
     *
     * Note: $target_adapter is an instance of the Filesystem
     *
     * @param   string $target_directory
     * @param   string $target_name
     * @param   bool   $replace
     * @param   string $target_adapter
     * @param   string $move_or_copy
     *
     * @return  $this
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function moveOrCopy
    (
        $target_directory,
        $target_name = '',
        $replace = false,
        $target_adapter,
        $move_or_copy = 'copy'
    ) {
        /** Defaults */
        if ($target_directory == '') {
            $target_directory = $this->parent;
        }

        if ($target_name == '' && $this->is_file) {
            if ($target_directory == $this->parent) {
                throw new RuntimeException
                (ucfirst(strtolower($this->getAdapterAdapter())) . ' Filesystem ' . $move_or_copy
                . ': Must specify new file name when using the same target path: ' . $this->path);
            }
            $target_name = $this->name;
        }

        if ($this->is_file === true) {
            $base_path = $this->parent;
        } else {
            $base_path = $this->path;
        }

        /** Edits */
        if (file_exists($this->path)) {
        } else {
            throw new RuntimeException
            (ucfirst(strtolower($this->getAdapterAdapter())) . ' Filesystem moveOrCopy: failed.'
            . 'This path does not exist: '
            . $this->path . ' Specified as source for ' . $move_or_copy
            . ' operation to ' . $target_directory);
        }

        if (file_exists($target_directory)) {
        } else {
            throw new RuntimeException
            (ucfirst(strtolower($this->getAdapterAdapter())) . ' Filesystem moveOrCopy: failed. '
            . ' This path does not exist: '
            . $this->path . ' Specified as destination for ' . $move_or_copy
            . ' to ' . $target_directory);
        }

        if (is_writeable($target_directory) === false) {
            throw new RuntimeException
            (ucfirst(strtolower($this->getAdapterAdapter()))
            . ' Filesystem Delete: No write access to file/path: ' . $target_directory);
        }

        if ($move_or_copy == 'move') {
            if (is_writeable($this->path) === false) {
                throw new RuntimeException
                (ucfirst(strtolower($this->getAdapterAdapter()))
                . ' Filesystem Delete: No write access for moving source file/path: '
                . $move_or_copy);
            }
        }

        if ($this->is_file === true || $target_name == '') {
        } else {
            if (is_dir($target_directory . '/' . $target_name)) {
            } else {

                new fsAdapter('write', $target_directory, $target_adapter,
                    $this->options = array('file' => $target_name)
                );
            }
            $target_directory = $target_directory . '/' . $target_name;
            $target_name      = '';
        }

        /** Create new target directories from source directories list */
        if (count($this->directories) > 0 && is_array($this->directories)) {

            asort($this->directories);

            foreach ($this->directories as $directory) {

                $new_path = $this->build_new_path($directory, $target_directory, $base_path);

                if (is_dir($new_path)) {
                } else {

                    $parent   = dirname($new_path);
                    $new_node = basename($new_path);

                    new fsAdapter('write', $parent, $target_adapter,
                        $this->options = array('file' => $new_node)
                    );
                }
            }
        }

        /** Copy files now that directories are in place */
        if (count($this->files) > 0 && is_array($this->files)) {

            foreach ($this->files as $file) {

                $new_path = $this->build_new_path($file, $target_directory, $base_path);

                /** Single file copy or move */
                if ($this->is_file === true) {
                    $file_name = $target_name;
                } else {
                    $file_name = basename($file);
                }

                /** Source */
                $adapter = new fsAdapter('Read', $file);
                $data    = $adapter->fs->data;

                /** Write Target */
                new fsAdapter('Write', $new_path, $target_adapter,
                    $this->options = array(
                        'file'    => $file_name,
                        'replace' => $replace,
                        'data'    => $data,
                    )
                );
            }
        }

        /** For move, remove the files and folders just copied */
        if ($move_or_copy == 'move') {
            $this->_deleteDiscoveryFilesArray();
            $this->_deleteDiscoveryDirectoriesArray();
        }

        return $this;
    }

    /**
     * Rename file or folder identified in path
     *
     * @param   string $path
     * @param   string $new_name
     *
     * @return  $this
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     * @api
     */
    public function rename($path, $new_name)
    {
        try {
            //

        } catch (Exception $e) {
            throw new RuntimeException('Local Filesystem: Rename Exception ' . $e->getMessage());
        }
    }

    /**
     * Create Directory
     *
     * @param   bool $path
     *
     * @return  void
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function createDirectory($path)
    {
        if (file_exists($path)) {
            return;
        }
        try {
            mkdir($path, $this->default_directory_permissions, true);
        } catch (Exception $e) {

            throw new RuntimeException
            ('Filesystem Create Directory: error creating directory: ' . $path);
        }

        return;
    }

    /**
     * Change owner for file or folder identified in path
     *
     * @param   string $path
     * @param   string $user_name
     * @param   bool   $recursive
     *
     * @return  $this
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     * @api
     */
    public function changeOwner($path, $user_name, $recursive = false)
    {
        if ($path == '') {
            throw new RuntimeException
            ('Filesystem Path is required, but was not provided.');
        }

        $options              = array();
        $options['user_name'] = $user_name;

        $this->setPath($path);
        $this->getMetadata();

        try {
            chown($this->path, $user_name);
        } catch (Exception $e) {

            throw new RuntimeException
            (ucfirst(strtolower($this->getAdapterAdapter()))
            . ' Filesystem: changeOwner method failed for Path: ' . $this->path
            . ' Owner: ' . $user_name);
        }
    }

    /**
     * Change group for file or folder identified in path
     *
     * @param   string $path
     * @param   string $group_id
     * @param   bool   $recursive
     *
     * @return  $this
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     * @api
     */
    public function changeGroup($path, $group_id, $recursive = false)
    {
        if ($path == '') {
            throw new RuntimeException
            ('Filesystem Path is required, but was not provided.');
        }

        $this->setPath($path);
        $this->getMetadata();

        try {
            chgrp($this->path, $group_id);
        } catch (Exception $e) {

            throw new RuntimeException
            (ucfirst(strtolower($this->getAdapterAdapter()))
            . ' Filesystem: changeGroup method failed for Path: ' . $this->path
            . ' Group: ' . $group_id);
        }

        return $this;
    }

    /**
     * Change permissions for file or folder identified in path
     *
     * @param   string $path
     * @param   int    $permission
     * @param   bool   $recursive
     *
     * @return  $this
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     * @api
     */
    public function changePermission($path, $permission, $recursive = false)
    {

        if ($path == '') {
            throw new RuntimeException
            ('Filesystem Path is required, but was not provided.');
        }

        $this->setPath($path);

        $this->getMetadata();

        try {
            chmod($this->path, octdec(str_pad($permission, 4, '0', STR_PAD_LEFT)));

            if ($permission === octdec(substr(sprintf('%o', fileperms($this->path)), - 4))) {
            } else {
                throw new RuntimeException
                ('File Permissions did not change to ' . $permission);
            }
        } catch (Exception $e) {

            throw new RuntimeException
            (ucfirst(strtolower($this->getAdapterAdapter()))
            . ' Filesystem: changePermission method failed for Path: ' . $this->path
            . ' Permissions: ' . octdec(str_pad($permission, 4, '0', STR_PAD_LEFT)));
        }

        return $this;
    }

    /**
     * Update the modification time and access time (touch) for the directory or file identified in the path
     *
     * @param   string $path
     * @param   int    $modification_time
     * @param   int    $access_time
     * @param   bool   $recursive
     *
     * @return  $this
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     * @api
     */
    public function touch($path, $modification_time = null, $access_time = null, $recursive = false)
    {
        if ($path == '') {
            throw new RuntimeException
            ('Filesystem Path is required, but was not provided.');
        }

        $this->setPath($path);

        $this->getMetadata();

        if ($modification_time == '' || $modification_time === null || $modification_time == 0) {
            $modification_time = null;
        }

        if ($access_time == '' || $access_time === null || $access_time == 0) {
            $access_time = null;
        }

        try {
            touch($this->path, $modification_time, $access_time);

            $hold = stat($this->path);

            if ($modification_time == $hold['mtime']) {
            } else {
                throw new RuntimeException ('Local Filesystem: Touch Failed.');
            }
        } catch (Exception $e) {

            throw new RuntimeException

            (ucfirst(strtolower($this->getAdapterAdapter()))
            . ' Filesystem: touch method failed for Path: ' . $this->path . ' ' . $e->getMessage());
        }

        return $this;
    }

    ////////// Begin Metadata /////////////

    /**
     * Set the Path
     *
     * @param   string $path
     *
     * @return  $this
     * @since   1.0
     */
    protected function setPath($path)
    {
        $this->path = $this->normalise($path);

        return $this;
    }

    /**
     * Get the Path
     *
     * @since   1.0
     * @return  string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Sets the absolute path, which is the relative path from the root directory,
     *  prepended with a '/'.
     *
     * @return  $this
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function setExists()
    {
        $this->exists = false;

        try {
            if (file_exists($this->path)) {
                $this->exists = true;
            } elseif (is_dir($this->path)) {
                $this->exists = true;
            }
        } catch (Exception $e) {
            throw new RuntimeException
            ('Local Filesystem: setExists Exception ' . $e->getMessage());
        }

        return $this;
    }

    /**
     * Returns the absolute path value
     *
     * @return  $this
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function getExists()
    {
        return $this->exists;
    }

    /**
     * Sets the absolute path, which is the relative path from the root directory,
     *  prepended with a '/'.
     *
     * @return  $this
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function setAbsolutePath()
    {
        if ($this->exists === false) {
            $this->absolute_path = null;

            return $this;
        }

        $this->absolute_path = realpath($this->path);

        return $this;
    }

    /**
     * Returns the absolute path value
     *
     * @return  bool
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function getAbsolutePath()
    {
        return $this->absolute_path;
    }

    /**
     * Set value as to whether or not the given path is absolute or not
     *
     * Relative path - describes how to get from a particular directory to a file or directory
     * Absolute Path - relative path from the root directory, prepended with a '/'.
     *
     * @return  $this
     * @since   1.0
     */
    protected function setIsAbsolutePath()
    {
        if ($this->exists === false) {
            $this->is_absolute_path = null;

            return $this;
        }

        if (substr($this->path, 0, 1) == '/') {
            $this->is_absolute_path = true;
            $this->absolute_path    = $this->path;
        } else {
            $this->is_absolute_path = false;
        }

        return $this;
    }

    /**
     * Returns the "is absolute path" value
     *
     * @return  bool
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function getIsAbsolutePath()
    {
        return $this->is_absolute_path;
    }

    /**
     * Is this the root folder?
     *
     * @return  $this
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function setIsRoot()
    {
        $this->is_root = false;

        if ($this->path == $this->root) {
            $this->is_root = true;
        } else {
            if ($this->path == '/' || $this->path == '\\') {
                $this->is_root = true;
            }
        }

        return $this;
    }

    /**
     * Returns the "is root" value
     *
     * @return  bool
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function getIsRoot()
    {
        return $this->is_root;
    }

    /**
     * Returns true or false indicator as to whether or not the path is a directory
     *
     * @return  $this
     * @since   1.0
     */
    protected function setIsDirectory()
    {
        $this->is_directory = false;

        if (is_dir($this->path)) {
            $this->is_directory = true;
        }

        return $this;
    }

    /**
     * Returns the "is absolute path" value
     *
     * @return  bool
     * @since   1.0
     */
    protected function getIsDirectory()
    {
        return $this->is_directory;
    }

    /**
     * Returns true or false indicator as to whether or not the path is a file
     *
     * @return  $this
     * @since   1.0
     */
    protected function setIsFile()
    {
        $this->is_file = false;

        if (is_file($this->path)) {
            $this->is_file = true;
        }

        return $this;
    }

    /**
     * Returns the "is file" value
     *
     * @return  bool
     * @since   1.0
     */
    protected function getIsFile()
    {
        return $this->is_file;
    }

    /**
     * Sets true or false indicator as to whether or not the path is a link
     *
     * @return  $this
     * @since   1.0
     */
    protected function setIsLink()
    {
        $this->is_link = false;

        if (is_link($this->path)) {
            $this->is_link = true;
        }

        return $this;
    }

    /**
     * Returns the "is link" value
     *
     * @return  bool
     * @since   1.0
     */
    protected function getIsLink()
    {
        return $this->is_link;
    }

    /**
     * Sets the value 'directory, 'file' or 'link' for the type determined
     *  from the path
     *
     * @return  $this
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function setType()
    {
        if ($this->exists === false) {
            $this->type = null;
        } elseif ($this->is_directory === true) {
            $this->type = 'directory';
        } elseif ($this->is_file === true) {
            $this->type = 'file';
        } elseif ($this->is_link === true) {
            $this->type = 'link';
        } else {

            throw new RuntimeException
            ('Not a directory, file or a link.');
        }

        return $this;
    }

    /**
     * Returns the value 'directory, 'file' or 'link' for the type determined
     *  from the path
     *
     * @return  string
     * @since   1.0
     */
    protected function getType()
    {
        return $this->type;
    }

    /**
     * Set Parent
     *
     * @return  $this
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function setParent()
    {
        if ($this->exists === false) {
            $this->parent = null;
        } elseif ($this->is_root === true) {
            $this->parent = null;
        } else {
            $this->parent = pathinfo($this->path, PATHINFO_DIRNAME);
        }

        return $this;
    }

    /**
     *  Get Parent
     *
     * @return  null|string
     * @since   1.0
     */
    protected function getParent()
    {
        return $this->parent;
    }

    /**
     * Set Name
     *
     * @return  $this
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function setName()
    {
        if ($this->exists === false) {
            $this->name = null;
        } else {

            $this->name = pathinfo($this->path, PATHINFO_BASENAME);
        }

        return $this;
    }

    /**
     * Get File or Directory Name
     *
     * @return  string
     * @since   1.0
     */
    protected function getName()
    {
        return $this->name;
    }

    /**
     * Set File Extension
     *
     * @return  $this
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function setExtension()
    {
        if ($this->exists === false) {
            $this->extension = null;

            return $this;
        }

        if ($this->is_file === true) {
        } elseif ($this->is_directory === true) {
            $this->extension = null;

            return $this;
        } else {
            throw new RuntimeException
            (ucfirst(strtolower($this->getAdapterAdapter())) . ' Filesystem:  not a valid file. Path: '
            . $this->path);
        }

        $this->extension = pathinfo($this->path, PATHINFO_EXTENSION);

        return $this;
    }

    /**
     * Get File Extension
     *
     * @return  string
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function getExtension()
    {
        return $this->extension;
    }

    /**
     * Set Name without Extension
     *
     * @return  $this
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function setNameNoExtension()
    {
        if ($this->exists === false) {
            $this->file_name_without_extension = null;

            return $this;
        }

        if ($this->is_file === true) {
        } elseif ($this->is_directory === true) {
            $this->file_name_without_extension = null;

            return $this;
        } else {
            throw new RuntimeException
            (ucfirst(strtolower($this->getAdapterAdapter())) . ' Filesystem:  not a valid file. Path: '
            . $this->path);
        }

        $this->file_name_without_extension = pathinfo($this->path, PATHINFO_FILENAME);

        return $this;
    }

    /**
     * Get Name without File Extension
     *
     * @return  string
     * @since   1.0
     */
    protected function getNameNoExtension()
    {
        return $this->file_name_without_extension;
    }

    /**
     * Get the size of file(s and folders)
     *
     * @param bool $recursive
     *
     * @return  $this
     * @since   1.0
     */
    protected function setSize($recursive = true)
    {
        $this->size = 0;

        if (count($this->files) > 0 && is_array($this->files)) {

            foreach ($this->files as $file) {
                $this->size = $this->size + filesize($file);
            }
        }

        return $this;
    }

    /**
     * Get the size of file(s and folders)
     *
     * @return  int
     * @since   1.0
     */
    protected function getSize()
    {
        return (int)$this->size;
    }

    /**
     * Set the mime type of the file located at path directory
     *
     * @return  $this
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function setMimeType()
    {
        $this->mime_type = null;

        if ($this->exists === false) {
            return $this;
        }

        if ($this->is_file === true) {
        } else {
            return $this;
        }

        if (function_exists('finfo_open')) {
            $php_mime        = finfo_open(FILEINFO_MIME);
            $this->mime_type = strtolower(finfo_file($php_mime, $this->path));
            finfo_close($php_mime);
        } elseif (function_exists('mime_content_type')) {
            $this->mime_type = mime_content_type($this->path);
        } else {
            throw new RuntimeException
            (ucfirst(strtolower($this->getAdapterAdapter())) . ' Filesystem:  getMimeType either '
            . ' finfo_open or mime_content_type are required in PHP');
        }

        return $this;
    }

    /**
     * Get the size of file(s and folders)
     *
     * @return  int
     * @since   1.0
     */
    protected function getMimeType()
    {
        return $this->mime_type;
    }

    /**
     * Set the owner of the file or directory defined in the path
     *
     * @return  $this
     * @since   1.0
     */
    protected function setOwner()
    {
        $this->owner = null;

        if ($this->exists === true) {
        } else {
            return $this;
        }

        $this->owner = fileowner($this->path);

        return $this;
    }

    /**
     * Get the owner
     *
     * @return  int
     * @since   1.0
     */
    protected function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set the group for the file or directory defined in the path
     *
     * @return  $this
     * @since   1.0
     */
    protected function setGroup()
    {
        $this->group = null;

        if ($this->exists === true) {
        } else {
            return $this->group;
        }

        $this->group = filegroup($this->path);

        return $this;
    }

    /**
     * Get the group
     *
     * @return  int
     * @since   1.0
     */
    protected function getGroup()
    {
        return (int)$this->group;
    }

    /**
     * Set Create Date for directory or file identified in the path
     *
     * @return  $this
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function setCreateDate()
    {
        $this->create_date = null;

        if ($this->exists === true) {
        } else {
            return $this;
        }

        try {
            $this->create_date = date("Y-m-d H:i:s", filectime($this->path));
        } catch (Exception $e) {

            throw new RuntimeException
            (ucfirst(strtolower($this->getAdapterAdapter())) . ' Filesystem getCreateDate failed for ' . $this->path);
        }

        return $this;
    }

    /**
     * Get the Create Date
     *
     * @return  null|string
     * @since   1.0
     */
    protected function getCreateDate()
    {
        return $this->create_date;
    }

    /**
     * Sets Last Access Date for directory or file identified in the path
     *
     * @return  string
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function setAccessDate()
    {
        $this->access_date = null;

        if ($this->exists === true) {
        } else {
            return $this;
        }

        try {
            $this->access_date = date("Y-m-d H:i:s", fileatime($this->path));
        } catch (Exception $e) {

            throw new RuntimeException
            (ucfirst(strtolower($this->getAdapterAdapter())) . ' Filesystem getAccessDate failed for ' . $this->path);
        }

        return $this;
    }

    /**
     * Get the Access Date
     *
     * @return  null|string
     * @since   1.0
     */
    protected function getAccessDate()
    {
        return $this->access_date;
    }

    /**
     * Set Modified Date for directory or file identified in the path
     *
     * @return  $this
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function setModifiedDate()
    {
        $this->modified_date = null;

        if ($this->exists === true) {
        } else {
            return $this;
        }

        try {
            $this->modified_date = date("Y-m-d H:i:s", filemtime($this->path));
        } catch (Exception $e) {
            throw new RuntimeException

            (ucfirst(strtolower($this->getAdapterAdapter())) . ' Filesystem:  getModifiedDate method failed for '
            . $this->path);
        }

        return $this;
    }

    /**
     * Retrieves Modified Date for directory or file identified in the path
     *
     * @return  null|string
     * @since   1.0
     */
    protected function getModifiedDate()
    {
        return $this->modified_date;
    }

    /**
     * Tests if the current user has read access
     *
     * @return  null|string
     * @since   1.0
     */
    protected function setIsReadable()
    {
        $this->is_readable = null;

        if ($this->exists === true) {
        } else {
            return $this;
        }

        $this->is_readable = is_readable($this->path);

        return $this;
    }

    /**
     * Gets Is Readable indicator
     *
     * @return  null|string
     * @since   1.0
     */
    protected function getIsReadable()
    {
        return $this->is_readable;
    }

    /**
     * Tests if the current user has write access
     *
     * @return  null|string
     * @since   1.0
     */
    protected function setIsWriteable()
    {
        $this->is_writable = null;

        if ($this->exists === true) {
        } else {
            return $this;
        }

        $this->is_writable = is_writable($this->path);

        return $this;
    }

    /**
     * Gets Is Writeable indicator
     *
     * @return  null|string
     * @since   1.0
     */
    protected function getIsWriteable()
    {
        return $this->is_writable;
    }

    /**
     * Sets indicator defining if the current user has executable access
     *
     * @return  $this
     * @since   1.0
     */
    protected function setIsExecutable()
    {
        $this->is_executable = null;

        if ($this->exists === true) {
        } else {
            return $this;
        }

        $this->is_executable = is_executable($this->path);

        return $this;
    }

    /**
     * Gets Is Writeable indicator
     *
     * @return  null|string
     * @since   1.0
     */
    protected function getIsExecutable()
    {
        return $this->is_executable;
    }

    /**
     * Sets md5 hash of a given file
     *
     * @return  $this
     * @since   1.0
     */
    protected function setHashFileMd5()
    {
        $this->hash_file_md5 = null;

        if ($this->exists === true) {
        } elseif ($this->is_file === true) {
        } else {
            return $this;
        }

        $this->hash_file_md5 = md5_file($this->path);

        return $this;
    }

    /**
     * Gets  the md5 hash of a given file
     *
     * @return  null|string
     * @since   1.0
     */
    protected function getHashFileMd5()
    {
        return $this->hash_file_md5;
    }

    /**
     * Set Hash file sha1
     * http://www.php.net/manual/en/function.sha1-file.php
     *
     * @return  $this
     * @since   1.0
     */
    protected function setHashFileSha1()
    {
        $this->hash_file_sha1 = null;

        if ($this->exists === true) {
        } elseif ($this->is_file === true) {
        } else {
            return $this;
        }

        $this->hash_file_sha1 = sha1_file($this->path);

        return $this;
    }

    /**
     * Gets the sha1 hash of a given file
     *
     * @return  null|string
     * @since   1.0
     */
    protected function getHashFileSha1()
    {
        return $this->hash_file_sha1;
    }

    /**
     * Set Hash file sha1 20
     * http://www.php.net/manual/en/function.sha1-file.php
     *
     * @return  $this
     * @since   1.0
     */
    protected function setHashFileSha1_20()
    {
        $this->hash_file_sha1_20 = null;

        if ($this->exists === true) {
        } elseif ($this->is_file === true) {
        } else {
            return $this;
        }

        $this->hash_file_sha1_20 = sha1_file($this->path, true);

        return $this;
    }

    /**
     * Gets the sha1 20 hash of a given file
     *
     * @return  null|string
     * @since   1.0
     */
    protected function getHashFileSha1_20()
    {
        return $this->hash_file_sha1_20;
    }

    /**
     * Common code for processing the discovery File Array
     *
     * @return  void
     * @since   1.0
     */
    private function _deleteDiscoveryFilesArray()
    {
        if (count($this->files) > 0 && is_array($this->files)) {
            foreach ($this->files as $file) {
                unlink($file);
            }
        }

        return;
    }

    /**
     * Discovery of folders and files for specified path
     *
     * @since   1.0
     * @return  $this
     */
    protected function discovery()
    {
        $this->directories = array();
        $this->files       = array();

        if ($this->exists === true) {
        } else {
            return $this;
        }

        if ($this->is_file === true) {
            $this->files[] = $this->path;
        }

        if (is_dir($this->path)) {
        } else {
            return $this;
        }

        if ($this->is_root === true) {
            return $this;
        }

        if (count(glob($this->path . '/*')) == 0) {
            return $this;
        }

        $this->directories[] = $this->path;

        $objects = new RecursiveIteratorIterator (
            new RecursiveDirectoryIterator($this->path),
            RecursiveIteratorIterator::SELF_FIRST);

        if (is_object($objects)) {
        } else {
            return $this;
        }

        foreach ($objects as $name => $object) {

            if (is_file($name)) {
                $this->files[] = $name;
            } elseif (is_dir($name)) {

                if (basename($name) == '.' || basename($name) == '..') {
                } else {
                    $this->directories[] = $name;
                }
            }
        }

        return $this;
    }

    /**
     * Common code for processing the discovery File Array
     *
     * @return  void
     * @since   1.0
     */
    private function _deleteDiscoveryDirectoriesArray()
    {
        if (count($this->directories) > 0 && is_array($this->directories)) {
            arsort($this->directories);
            foreach ($this->directories as $directory) {
                rmdir($directory);
            }
        }

        return;
    }

    /**
     * Convert the path into a path relative to the path passed in
     *
     * @param   string $relative_to_this_path
     *
     * @return  $this
     * @throws  \CommonApi\Exception\RuntimeException
     * @since   1.0
     */
    protected function getRelativePath($relative_to_this_path = '')
    {
        $hold   = getcwd();
        $target = $this->normalise($relative_to_this_path);
        chdir($target);
        $this->data = realpath($this->path);
        chdir($hold);

        return $this;
    }
}
