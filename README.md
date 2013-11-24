=======
Filesystem
=======

[![Build Status](https://travis-ci.org/Molajo/Filesystem.png?branch=master)](https://travis-ci.org/Molajo/Filesystem)

Simple, uniform file and directory services API for PHP applications for interacting with different filesystems in a common way.

## System Requirements ##

* PHP 5.3, or above
* [PSR-0 compliant Autoloader](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md)
* PHP framework independent

## At a glance ... ##
This is a quick look at the Filesystem API, more information on each method can be found, below.
First, the application connects to a [Filesystem](https://github.com/Molajo/Filesystem#filesystem-adapter-handlers). From that point on,
reading, writing, listing, copying, moving, etc., files and folders is the same for the application, regardless
of the underlying system. With the exception of the read method, each are designed to provide results for either
a file or a folder. Since Exceptions can be thrown, it is recommended methods be used within Try/Catch blocks.

[Filesystem Adapter Interface](https://github.com/Molajo/Standard/blob/master/Vendor/Molajo/Filesystem/Api/FilesystemInterface.php)
```php

    // Instantiate Handler (example local) and pass it into the Filesystem Adapter

    use Molajo\Filesystem\Handler\Local;
    $handler = new Local();

    use Molajo\Filesystem\Adapter;
    $adapter = new Adapter($handler);

    $true_or_false = $adapter->exists('\file\located\here.txt');

    $metadata = $adapter->getMetadata('\file\located\here.txt');
    echo $metadata->owner;      // See complete list of metadata returned, below

    $contents_of_file = $adapter->read('\file\located\here.txt');

    $list_of_results = $adapter->getList($path, $recursive, $extension_list,
        $include_files, $include_folders, $filename_mask);

    $adapter->write($path, $data, $replace, $append, $truncate);

    $adapter->delete($path, $recursive);

    $adapter->copy($path, $target_directory, $target_name, $replace, $target_handler);

    $adapter->move($path, $target_directory, $target_name, $replace, $target_handler);

    $adapter->rename($path, $new_name);

    $adapter->changeOwner($path, $user_name, $recursive);

    $adapter->changeGroup($path, $group_id, $recursive);

    $adapter->changePermission($path, $permission, $recursive);

    $adapter->touch($path, $modification_time, $access_time, $recursive);

```

## What is Filesystem? ##

**Filesystem** provides a common API for File and Folder operations, including: exists, getMetadata, read, getList,
write, delete, copy, move, and rename on, and between, filesystems. In addition, applications can perform
basic system administrative tasks like changing the owner, group, permissions, lasted updated, and touch dates for
files and folders.

### Class Instantiation ####
After instantiating the adapter class for a specific filesystem, the application can then interact with that filesystem.

The local filesystem is default and does require any input. Other filesystems require specific types of input, as described in the **Filesystem Handlers** section, below.

```php

    use Molajo\Filesystem\Handler\Local;
    $handler = new Local();

    use Molajo\Filesystem\Adapter;
    $adapter = new Adapter($handler);

```

### Using the Filesystem Adapter ###
Applications can use $adapter to interact with files and folders as in this example of reading a file.

```php
    try {
        $results = $adapter->read('\file\located\here.txt');

    } catch (Exception $e) {
        // deal with the exception
    }
```

## Filesystem API ##
Listed are the various methods available, an example, parameters definitions and how to access the results. It is recommended to use each method in a Try/Catch block since the method could throw an exception.

### Exists ###
Verifies if the file or folder defined by $path exists, returns true or false.

```php
    try {
        $exists = $adapter->exists($path);
    } catch (Exception $e) {
        // deal with the exception
    }

    if ($exists === true) {
			echo 'The file or folder defined in $path does exist.';
    } else {
			echo 'The file or folder defined in $path does NOT exist.';
    }
```
**Parameters**
- **$path** contains an absolute path for a file or folder

### getMetadata ###
Retrieves an object containing metadata for the file or folder defined in $path:

```php
    try {
        $metadata = $adapter->getMetadata($path);
    } catch (Exception $e) {
        // deal with the exception
    }

    // View all object properties returned


    // Use a single element
    echo $metadata->name;
```
**Parameters**
- **$path** contains an absolute path for a file or folder

#### Metadata ####
The metadata available for each type of Filesystem can vary. For the Local Filesystem, this is the list of metadata available.

**Metadata about the Filesystem** handler, root, persistence, default_directory_permissions,
default_file_permissions and read_only.

**Metadata about requested path** (be it a file or folder) path, is_absolute, absolute_path, exists, owner,
group, create_date, access_date, modified_date, is_readable, is_writable, is_executable, is_directory,
is_file, is_link, type, name, parent, extension, file_name_without_extension, size and mime_type.

### Read ###
To read a specific file from a filesystem:

```php
    try {
        $results = $adapter->read('\file\located\here.txt');
    } catch (Exception $e) {
        // deal with the exception
    }
```

**Parameters**
- **$path** contains an absolute path for a file or folder

### Get List ###

Returns an array of files and folders for a given path, optionally recursively processing all sub-folders, and optionally limiting the results to those file extensions identified.

```php
    $path = '\file\located\here.txt';
    $recursive = true;
    $extension_list = 'txt, pdf, doc';
    $include_files = false;
    $include_folders = false;
    $filename_mask = null;

    try {
        $results = $adapter->getList($path, $recursive, $extension_list,
            $include_files, $include_folders, $filename_mask);
    } catch (Exception $e) {
        // deal with the exception
    }
```
**Parameters**
- **$path** contains an absolute path for a file or folder
- **$recursive** true (default) or false indicator of whether or not subfolders should be processed
- **$extension_list** Comma delimited list of file extensions which qualify
- **$include_files** True or false value used to exclude or include files in list
- **$include_folders** True or false value used to exclude or include folders in list

### Write ###

Writes data to the file identified in the given path, optionally replacing existing data, appending to the
    data that already exists, or truncating the data in the file, but leaving the empty file in place.

```php
    $path = '\file\located\here.txt';
    $data = 'Write this data.';
    $replace = true;
    $append = false;
    $truncate = false;

    try {
        $results = $adapter->write($path, $data, $replace, $append, $truncate);

    } catch (Exception $e) {
        // deal with the exception
    }
```
**Parameters**
- **$path** contains an absolute path for the file
- **$data** Content to be written to the file
- **$replace** True or false value indicating whether or not an existing file should be overwritten
- **$append** True or false value indicating whether or not data should be appended to existing data in table
- **$truncate** True or false value indicating whether or not an existing data in the file should be truncated, leaving an empty file

### Delete ###

Deletes the folder and/or files identified in the given path, recursively deleting subfolders and files if so specified.

```php
    $path = '\example\of\a\folder';
    $recursive = true;

    try {
        $results = $adapter->delete($path, $recursive);
    } catch (Exception $e) {
        // deal with the exception
    }
```
**Parameters**
- **$path** contains an absolute path for the folder or file
- **$recursive** True or false value indicating if subfolders and files should be deleted, too (only valid for folder). False value for folder with subfolders will throw an exception.

### Copy ###

Copies the file(s) and folder(s) from a filesystem to a location on the same or a different filesystem.

When copying a single file, a new filename can be specified using the target_name field.

```php

    $path                   = '\copy\contents\from\this\folder';
    $target_directory       = '\to\this\folder';
    $target_name            = null;
    $replace            	= true;
    $target_handler = 'Backup';

    try {
        $adapter->copy($path, $target_directory, $target_name, $replace, $target_handler);
    } catch (Exception $e) {
        // deal with the exception
    }

```
**Parameters**
- **$path** contains an absolute path for the source folder or file
- **$target_directory** contains an absolute path for the target folder or file
- **$target_name** contains a different value for filename when copying a file a new filename is desired
- **$replace** True or false value indicating whether the target file, if exists, should be replaced.
- **$target_handler** Use if copying to a filesystem other than the current filesystem.

### Move ###

Moves the file(s) and folder(s) from a filesystem to a location on the same or a different filesystem.

When moving a single file, a new filename can be specified using the target_name field.

```php
    $path                   = '\move\contents\from\this\folder';
    $target_directory       = '\to\this\folder';
    $target_name            = null;
    $replace            	= false;
    $target_handler = 'Archive';

    try {
        $adapter->move($path, $target_directory, $target_name, $replace, $target_handler);
    } catch (Exception $e) {
        // deal with the exception
    }
```
**Parameters**
- **$path** contains an absolute path for the source folder or file
- **$target_directory** contains an absolute path for the target folder or file
- **$target_name** contains a different value for filename when copying a file a new filename is desired
- **$replace** True or false value indicating whether the target file, if exists, should be replaced.
- **$target_handler** Use if copying to a filesystem other than the current filesystem.

### Rename ###
Renames the file or folder to the specified value.

```php
    $path                   = '\copy\this\folder\old_name.txt';
    $new_name               = 'new_name.txt';

    try {
        $adapter->rename($path, $new_name);
    } catch (Exception $e) {
        // deal with the exception
    }
```
**Parameters**
- **$path** contains an absolute path for the source folder or file
- **$target_name** contains the new value to use for filename

### Change Owner ###
Change owner for file or folder identified in path, recursively changing the owner for all subordinate files and folders, if specified

```php
    $path        = '\this\folder\';
    $user_name   = 'user';
    $recursive   = true;

    try {
        $adapter->changeOwner($path, $user_name, $recursive);
    } catch (Exception $e) {
        // deal with the exception
    }
```
**Parameters**
- **$path** contains an absolute path for the folder or file
- **$user_name** system user name
- **$recursive** True or false value indicating if subfolders and files should be deleted, too (only valid for folder). False value for folder with subfolders will throw an exception.

### Change Group ###
Change group for file or folder identified in path, recursively changing the group for all subordinate files and folders, if specified

```php
    $path        = '\this\folder\';
    $group_id    = 5;
    $recursive   = true;

    try {
        $adapter->changeGroup($path, $group_id, $recursive);
    } catch (Exception $e) {
        // deal with the exception
    }
```
**Parameters**
- **$path** contains an absolute path for the folder or file
- **$group_id** numeric system group id
- **$recursive** True or false value indicating if subfolders and files should be deleted, too (only valid for folder). False value for folder with subfolders will throw an exception.

### Change Permission ###
Change permission for file or folder identified in path, recursively changing the permission for all subordinate files and folders, if specified

```php
    $path        = '\this\folder\';
    $permission  = 0755;
    $recursive   = true;

    try {
        $adapter->changePermission($path, $permission, $recursive);
    } catch (Exception $e) {
        // deal with the exception
    }
```
**Parameters**
- **$path** contains an absolute path for the folder or file
- **$permission** system user name
- **$recursive** True or false value indicating if subfolders and files should be deleted, too (only valid for folder). False value for folder with subfolders will throw an exception.

### Touch ###
Update the modification time and access time for the directory or file identified in $path.

```php
    $path                = '\copy\this\folder\old_name.txt';
    $modification_time   = $modification_time;
    $access_time         = $access_time;
    $recursive           = false;

    try {
        $adapter->touch($path, $modification_time, $access_time, $recursive);
    } catch (Exception $e) {
        // deal with the exception
    }
```
**Parameters**
- **$path** contains an absolute path for the source folder or file
- **$modification_time** contains a PHP time value to be used as the modification time
- **$access_time** contains a PHP time value to be used as the access time
- **$recursive** true or false value indicating if the changes should be recursively applied to subfolders and files (only for folder)

## Filesystem Handlers ##

To use a filesystem, the application instantiates the Connection class, passing in a
request for a specific filesystem adapter handler. Different types of filesystems require different input
to access the environment. For example, some systems require username and password authentication. Startup
properties are passed into the filesystem adapter handler using the $options array.

### Local Filesystem Handler ###

- **$options** Associative array of named pair values needed to establish a connection for the adapter handler;
- **$handler** Identifier for the file system. Examples include Local (default), Ftp, Media, Dropbox, etc.;

### FTP Filesystem Handler ###
This shows how to backup a file on one filesystem to another filesystem.

```php
    $options = array(
        'source_handler' => 'local',
        'source_path'    => '/x/y/example',
        'target_handler' => 'ftp',
        'target_path'    => '/x/y/backup',
        'archive'        => 'zip'
    );

    $adapter = new \Molajo\Filesystem\File($options);
    $data    = $adapter->backup ();
```
**Parameters**
- **$path** contains an absolute path for the source folder or file
- **$target_name** contains the new value to use for filename

### Media Filesystem Handler ###

## Creative Uses of Filesystem ##

### Merged Filesystems ###
You can use **Filesystem** to easily create a merged filesystem: Just instantiate multiple classes and merge read results.

### Backup ###
**Filesystem** supports copying files and folders to another filesystem. Combining this capability with an application cron job is a good way to schedule backups.

### Archive ###
**Filesystem** supports moving files and folders to another filesystem, simplifying the process of content archival.

### Upload and Download ###

## Install using Composer from Packagist ##

### Step 1: Install composer in your project ###

```php
    curl -s https://getcomposer.org/installer | php
```

### Step 2: Create a **composer.json** file in your project root ###

```php
{
    "require": {
        "Molajo/Filesystem": "1.*"
    }
}
```

### Step 3: Install via composer ###

```php
    php composer.phar install
```

About
=====

Molajo Project has adopted the following:

 * [Semantic Versioning](http://semver.org/)
 * [PSR-0 Autoloader Interoperability](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md)
 * [PSR-1](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md)
 and [PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)
 * [phpDocumentor2] (https://github.com/phpDocumentor/phpDocumentor2)
 * [phpUnit Testing] (https://github.com/sebastianbergmann/phpunit)
 * [Travis Continuous Improvement] (https://travis-ci.org/profile/Molajo)
 * [Packagist] (https://packagist.org)


Submitting pull requests and features
------------------------------------

Pull requests [GitHub](https://github.com/Molajo/Filesystem/pulls)

Features [GitHub](https://github.com/Molajo/Filesystem/issues)

Author
------

Amy Stephen - <AmyStephen@gmail.com> - <http://twitter.com/AmyStephen><br />
See also the list of [contributors](https://github.com/Molajo/Filesystem/contributors) participating in this project.

License
-------

**Molajo Filesystem** is licensed under the MIT License - see the `LICENSE` file for details

Acknowledgements
----------------

**W3C File API: Directories and System** [W3C Working Draft 17 April 2012 → ](http://www.w3.org/TR/file-system-api/)
specifications were followed, as closely as possible.

More Information
----------------
- [Extend](/Filesystem/doc/extend.md)
