<?php
/**
 * Filesystem Exception
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Filesystem;

defined ('MOLAJO') or die;

/**
 * Filesystem Exception
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 *
 * Full interface specification:
 *  See https://github.comsrc/Molajo/Filesystem/doc/speifications.md
 */
Class FilesystemException extends \Exception
{

    /**
     * Construct
     *
     * @return  void
     * @since   1.0
     */
    public function __construct ()
    {

    }

}

/**
 *
EncodingError
 * A path or URL supplied to the API was malformed.
InvalidModificationError
 * The modification requested was illegal. Examples of invalid modifications include moving a directory
 * into its own child, moving a file into its parent directory without changing its name, or copying a
 * directory to a path occupied by a file.
InvalidStateError
 * An operation depended on state cached in an interface object,
 * but that state that has changed since it was read from disk.
NotFoundError
 * A required file or directory could not be found at the time an operation was processed.
NotReadableErr
 * A required file or directory could be read.
NoModificationAllowedError
 * The user attempted to write to a file or directory which could not be modified due to the state of the underlying filesystem.
PathExistsError
 * The user agent failed to create a file or directory due to the existence of a file or directory with the same path.
QuotaExceededError
 * The operation failed because it would cause the application to exceed its storage quota.
SecurityError

A required file was unsafe for access within a Web application
Too many calls are being made on filesystem resources

This is a security error code to be used in situations not covered by any other error codes.
TypeMismatchError 	The user has attempted to look up a file or directory, but the Entry found is of the wrong type [e.g. is a DirectoryEntry when the user requested a FileEntry].
 */


class FileNotFoundException extends \Exception {}
