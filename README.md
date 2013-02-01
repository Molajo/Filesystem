**NOT COMPLETE**

=======
Filesystem
=======

[![Build Status](https://travis-ci.org/Molajo/Filesystem.png?branch=master)](https://travis-ci.org/Molajo/Filesystem)

General-purpose file and directory services package for PHP applications using the same API with different filesystems.

## System Requirements

* PHP 5.3, or above
* PSR-0 compliant autoloader
* Platform independent
* [optional] PHPUnit 3.5+ to execute the test suite (phpunit --version)

## Basic Usage

### Filesystems

The value of **Filesystem** is that the application code which requests files and
directories be created, read, updated, deleted, copied, and so on, does not have
to change when the system that manages the files and directories changes.

The following commands can be used for a local filesystem, FTP server, Cache storage,
 and so on. This is accomplished by defining Adapters which interact with **Filesystems**
 to perform tasks.

### Read

To read a specific file from a filesystem:

```php
    $connect = new \Molajo\Filesystem\Adapter('Local', 'location/of/file.txt', 'Read');
    $results = $connect->fs->action_results);
```

### Metadata

To access metadata for a filesystem request:

```php
   $results = $connect->fs->is_writable);
```
**Metadata about the Fileystem**
* filesystem_type
* root
* persistence
* directory_permissions (default)
* file_permissions (default)
* read_only

**Metadata about path requested (file or folder)**
* path
* exists
* owner
* group
* create_date
* access_date
* modified_date
* is_readable
* is_writable
* is_executable
* absolute_path
* is_absolute
* is_directory
* is_file
* is_link
* type
* name
* parent
* extension
* size
* mime_type

### List

To list the names of files and/or directories from a filesystem for a given path:


```php
    $connect = new \Molajo\Filesystem\Adapter('Local', 'directory-name', 'List', $options);
    $results = $connect->fs->action_results);
```

### Write

To write a file to a filesystem. :

```php
    $options = array(
        'file'    => 'filename.txt',
        'replace' => false,
        'data'    => 'Here are the words to write to the file.',
    );
    $connect      = new \Molajo\Filesystem\Adapter('Local', 'name/of/folder/here', 'Write', $options);
```

### Copy

To write a file or folder to a specific destination on the filesystem (or a different filesystem)::

```php
    $options = array(
        'target_directory'       => 'name/of/target/folder',
        'target_name'            => 'single-file-copy.txt',
        'replace'                => false,
        'target_filesystem_type' => 'FTP'
    );
    $connect = new \Molajo\Filesystem\Adapter('Local', 'name/of/source/folder', 'Copy', $options);
```

### Move

To move a file or folder to a specific destination on the filesystem:

```php
    $options = array(
        'target_directory'       => 'name/of/target/folder',
        'target_name'            => 'single-file-move.txt',
        'replace'                => false,
        'target_filesystem_type' => 'Local'
    );
    $connect = new \Molajo\Filesystem\Adapter('Local', 'name/of/source/folder', 'Move', $options);
```

### Delete

To move a file or folder to a specific destination on the filesystem:

```php
    $connect = new \Molajo\Filesystem\Adapter('Local', 'name/of/source/folder', 'Delete');
```

### DirectoryIterator
http://us2.php.net/manual/en/class.filesystemiterator.php

### Merged Filesystems

Many times, a developer must work with groups of files that are located in different folders and potentially
in a different filesystem. **Merged filesystems** allow you to define a set of directories as though it were
the same location in order to use the output together.

```php
    $options = array(
        'source_adapter' => 'local',
        'source_path'    => '/x/y/example.txt'
    );

    $connect = new \Molajo\Filesystem\File($options);
    $data    = $connect->read ();
```
### RecursiveTreeIterator

http://us2.php.net/manual/en/class.recursivetreeiterator.php
http://us2.php.net/manual/en/class.recursivetreeiterator.php

## Special Purpose File Operations

### Backup

This shows how to backup a file on one filesystem to another filesystem.

```php
    $options = array(
        'source_adapter' => 'local',
        'source_path'    => '/x/y/example',
        'target_adapter' => 'ftp',
        'target_path'    => '/x/y/backup',
        'archive'        => 'zip'
    );

    $connect = new \Molajo\Filesystem\File($options);
    $data    = $connect->backup ();
```

### Download

This shows how to backup a file on one filesystem to another filesystem.

```php
    $options = array(
        'source_adapter' => 'local',
        'source_path'    => '/x/y/example',
        'target_adapter' => 'ftp',
        'target_path'    => '/x/y/backup',
        'archive'        => 'zip'
    );

    $connect = new \Molajo\Filesystem\File($options);
    $data    = $connect->backup ();
```

### FTP Server

This shows how to backup a file on one filesystem to another filesystem.

```php
    $options = array(
        'source_adapter' => 'local',
        'source_path'    => '/x/y/example',
        'target_adapter' => 'ftp',
        'target_path'    => '/x/y/backup',
        'archive'        => 'zip'
    );

    $connect = new \Molajo\Filesystem\File($options);
    $data    = $connect->backup ();
```

## Installation

### Install using Composer from Packagist

**Step 1** Install composer in your project:

```php
    curl -s https://getcomposer.org/installer | php
```

**Step 2** Create a **composer.json** file in your project root:

```php
{
    "require": {
        "Molajo/Filesystem": "1.*"
    }
}
```

**Step 3** Install via composer:

```php
    php composer.phar install
```

**Step 4** Add this line to your application’s **index.php** file:

```php
    require 'vendor/autoload.php';
```

This instructs PHP to use Composer’s autoloader for **Filesystem** project dependencies.

### Or, Install Manually

Download and extract **Filesystem**.

Copy the Molajo folder (first subfolder of src) into your Vendor directory.

Register Molajo\Filesystem\ subfolder in your autoload process.

About
=====

Molajo Project adopted the following:

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

Pull requests [GitHub](https://github.com/Molajo/Fileservices/pulls)

Features [GitHub](https://github.com/Molajo/Fileservices/issues)

Author
------

Amy Stephen - <AmyStephen@gmail.com> - <http://twitter.com/AmyStephen><br />
See also the list of [contributors](https://github.com/Molajo/Fileservices/contributors) participating in this project.

License
-------

**Molajo Filesystem** is licensed under the MIT License - see the `LICENSE` file for details

Acknowledgements
----------------

**W3C File API: Directories and System** [W3C Working Draft 17 April 2012 → ](http://www.w3.org/TR/file-system-api/)
specifications were followed, as closely as possible.

More Information
----------------
- [Usage](/Filesystem/doc/usage.md)
- [Extend](/Filesystem/doc/extend.md)
- [Specifications](/Filesystem/doc/specifications.md)
