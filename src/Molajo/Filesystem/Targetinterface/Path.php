<?php
/**
 * Abstract Path Class for Filesystem Adapter
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem\Targetinterface;

defined('MOLAJO') or die;

use Exception;

use Molajo\Filesystem\Exception\AccessDeniedException as AccessDeniedException;
use Molajo\Filesystem\Exception\AdapterNotFoundException as AdapterNotFoundException;
use Molajo\Filesystem\Exception\FileException as FileException;
use Molajo\Filesystem\Exception\FileExceptionInterface as FileExceptionInterface;
use Molajo\Filesystem\Exception\FileNotFoundException as FileNotFoundException;
use Molajo\Filesystem\Exception\InvalidPathException as InvalidPathException;

/**
 * Abstract Path Class for Filesystem Adapter
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
abstract class Path extends System implements PathInterface
{
    /**
     * Constructor
     *
     * @param   array  $options
     *
     * @since   1.0
     */
    public function __construct($filesystem_type)
    {
        parent::__construct($filesystem_type);

        return $this;
    }

    /**
     * Get the Path
     *
     * @return  string
     * @since   1.0
     */
    public function getPath()
    {
        $this->path = $this->filesystem_type->getPath();

        return $this->path;
    }

    /**
     * Does the path exist (either as a file or a folder)?
     *
     * @return bool|null
     */
    public function exists()
    {
        $this->exists = $this->filesystem_type->exists();

        return $this->exists;
    }

    /**
     * Retrieves the absolute path, which is the relative path from the root directory,
     *  prepended with a '/'.
     *
     * @return  string
     * @since   1.0
     */
    public function getAbsolutePath()
    {
        $this->absolute_path = $this->filesystem_type->getAbsolutePath();

        return $this->absolute_path;
    }

    /**
     * Indicates whether the given path is absolute or not
     *
     * Relative path - describes how to get from a particular directory to a file or directory
     * Absolute Path - relative path from the root directory, prepended with a '/'.
     *
     * @return  bool
     * @since   1.0
     */
    public function isAbsolute()
    {
        $this->is_absolute = $this->filesystem_type->isAbsolute();

        return $this->is_absolute;
    }

    /**
     * Returns true or false indicator as to whether or not the path is a directory
     *
     * @return  bool
     * @since   1.0
     */
    public function isDirectory()
    {
        $this->is_directory = $this->filesystem_type->isDirectory();

        return $this->is_directory;
    }

    /**
     * Returns true or false indicator as to whether or not the path is a file
     *
     * @return  bool
     * @since   1.0
     */
    public function isFile()
    {
        $this->is_file = $this->filesystem_type->isFile();

        return $this->is_file;
    }

    /**
     * Returns true or false indicator as to whether or not the path is a link
     *
     * @return  bool
     * @since   1.0
     */
    public function isLink()
    {
        $this->is_link = $this->filesystem_type->isLink();

        return $this->is_link;
    }

    /**
     * Returns the value 'directory, 'file' or 'link' for the type determined
     *  from the path
     *
     * @return  null
     * @since   1.0
     * @throws  FileException
     */
    public function getType()
    {
        if ($this->is_directory()) {
            $this->type = 'directory';
            return $this->type;
        }

        if ($this->is_path) {
            $this->type = 'file';
            return $this->type;
        }

        if ($this->is_link) {
            $this->type = 'link';
            return $this->type;
        }

        throw new FileException ('Not a directory, file or a link.');
    }

    /**
     * Get File or Directory Name
     *
     * @return  bool|null
     * @since   1.0
     * @throws  FileNotFoundException
     */
    public function getName()
    {
        $this->name = $this->filesystem_type->getName();

        return $this->name;
    }

    /**
     * Get Parent
     *
     * @return  bool|null
     * @since   1.0
     * @throws  FileNotFoundException
     */
    public function getParent()
    {
        $this->parent = $this->filesystem_type->getParent();

        return $this->parent;
    }

    /**
     * Get File Extension
     *
     * @return  bool|null
     * @since   1.0
     * @throws  FileNotFoundException
     */
    public function getExtension()
    {
        $this->extension = $this->filesystem_type->getExtension();

        return $this->extension;
    }

    /**
     * Get the file size of a given file.
     *
     * @return  int
     * @since   1.0
     */
    public function getSize()
    {
        $this->size = $this->filesystem_type->getSize();

        return $this->size;
    }

    /**
     * Returns the owner of the file or directory defined in the path
     *
     * @return  bool
     * @since   1.0
     */
    public function getOwner()
    {
        $this->owner = $this->filesystem_type->getOwner();

        return $this->owner;
    }

    /**
     * Returns the group for the file or directory defined in the path
     *
     * @return  string
     * @since   1.0
     */
    public function getGroup()
    {
        $this->group = $this->filesystem_type->getOwner();

        return $this->group;
    }

    /**
     * Retrieves Create Date for directory or file identified in the path
     *
     * @return  null
     * @since   1.0
     * @throws  FileException
     */
    public function getCreateDate()
    {
        $this->create_date = $this->filesystem_type->getCreateDate();

        return $this->create_date;
    }

    /**
     * Retrieves Last Access Date for directory or file identified in the path
     *
     * @return  null
     * @since   1.0
     * @throws  FileException
     */
    public function getAccessDate()
    {
        $this->access_date = $this->filesystem_type->getAccessDate();

        return $this->access_date;
    }

    /**
     * Retrieves Last Update Date for directory or file identified in the path
     *
     * @return  null
     * @since   1.0
     * @throws  FileException
     */
    public function getModifiedDate()
    {
        $this->modified_date = $this->filesystem_type->getModifiedDate();

        return $this->modified_date;
    }

    /**
     * Tests if the group specified: 'owner', 'group', or 'world' has read access
     *  Returns true or false
     *
     * @return  bool
     * @since   1.0
     */
    public function isReadable()
    {
        $this->is_readable = $this->filesystem_type->isReadable();

        return $this->is_readable;
    }

    /**
     * Tests if the group specified: 'owner', 'group', or 'world' has write access
     *  Returns true or false
     *
     * @return  bool
     * @since   1.0
     */
    public function isWriteable()
    {
        $this->is_writeable = $this->filesystem_type->isWriteable();

        return $this->is_writeable;
    }

    /**
     * Tests if the group specified: 'owner', 'group', or 'world' has execute access
     *  Returns true or false
     *
     * @return  null
     * @since   1.0
     */
    public function isExecutable()
    {
        $this->is_executable = $this->filesystem_type->isExecutable();

        return $this->is_executable;
    }

    /**
     * Returns the mime type for this file.
     *
     * @throws \RuntimeException if finfo failed to load and/or mim_content_type
     * is unavailable
     * @throws \LogicException if the mime type could not be interpreted from
     * the output of finfo_file
     *
     * @return string
     */
    public function getMimeType()
    {
        return;

        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            if (! $finfo) {
                throw new \RuntimeException('Failed to open finfo');
            }

            $mime = strtolower(finfo_file($finfo, $this->getPathname()));
            finfo_close($finfo);

            if (! preg_match(
                '/^([a-z0-9]+\/[a-z0-9\-\.]+);\s+charset=(.*)$/',
                $mime,
                $matches
            )
            ) {
                throw new \LogicException(
                    'An error parsing the MIME type "' . $mime . '".'
                );
            }

            return $matches[1];
        } elseif (function_exists('mime_content_type')) {
            return mime_content_type($this->getPathname());
        }

        throw new \RuntimeException(
            'The finfo extension or mime_content_type function are needed to '
                . 'determine the Mime Type for this file.'
        );
    }
}
