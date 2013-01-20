**NOT COMPLETE**

Using Filesystem
=============

**Filesystem** is automatically included in Molajo applications but available for use by any PHP application
on Packagist ([Molajo/Filesystem](http://packagist.org/packages/molajo/filesystem)), installable via
[Composer](http://getcomposer.org/). **Filesystem**

## What does it do?

**Filesystem** is an object-oriented PHP package to handle file and directory services for different types
of filesystems through use of Adapters which make it possible for the application to process files
using the same application code, regardless of whether the file resides on a local filesystem, an FTP Server
or a cloud-based platform.

This flexibility makes it easier for developers to simplify backups processes, introduce storage partitioning
by user in an application, or switch out filesystems, when needed, for performance gains or reduce cost, all
without impact to application code.

## System Requirements

* PSR-0 capable autoloader
* PHP 5.3, or above
* [optional] PHPUnit 3.5+ to execute the test suite (phpunit --version)

## Installation

**Filesystem** is available on Packagist ([Molajo/Filesystem](http://packagist.org/packages/molajo/filesystem))
and as such installable via [Composer](http://getcomposer.org/).

If you do not use Composer, you can download the code from ([Github](https://github.com/Molajo/Filesystem)).
Link to ([manual install steps](https://github.com/Molajo/Filesystem)).

### Autoloader

PSR-0 compatible autoloader (e.g. the [Symfony2 ClassLoader component](https://github.com/symfony/ClassLoader))
to load **Filesystem** classes.

## Basic Usage

This simple example shows how to **read** a text file at *'/x/y/zebra.txt'* on the **local filesystem**.

**Step 1** connect to the local fileserver adapter.

```php
use Molajo\Filesystem\Adapter\Local as LocalAdapter;
$adapter = new LocalAdapter();
```
**Step 2** Connect to the files and directories on the local filesystem, injecting the adapter into the constructor.

```php
use Molajo\Filesystem\Access\File as Files;
$files = new Files($adapter);
 ```

**Step 3** Now, make the request for the local filesystem to read the file.

```php
$data = $files->read('/x/y/zebra.txt');
 ```

And that's all there is to it. All the verification that it's a file, that the path exists, that the user has
read permission, and so on, are taken care of for you.

### Basic File Services

```php
$data = $local->read('path\to\your\file');

$local->write('path\to\your\file');

$local->delete('path\to\your\file');

```

### Backups

This shows how to backup a file on one filesystem to another filesystem.

```php

use Molajo\Filesystem\Adapter\Local as LocalAdapter;
$adapter = new LocalAdapter();

use Molajo\Filesystem\Access\File as Files;
$files = new Files($adapter);


// Backup a folder from the local system to the cloud
$results = $file->copy('path/to/local/folder', 'path/to/cloud/destination', 'local', 'cloud');
```

### Basic Directory Services
```
<?php

// List of Files in Directory
$data = $file->read('path\to\your\file');

// Recursively list all files and subfolders within a Directory
$data = $file->read('path\to\your\file');

// Copy a folder
$data = $file->write('path\to\your\file');

// Move a folder
$data = $file->write('path\to\your\file');

// Delete a file
$results = $file->write('path\to\your\file');

```

More file services...

### Exception Handling
```
<?php

// Read a File
$data = $file->read('path\to\your\file');

// Create a new file or save an existing file
$data = $file->write('path\to\your\file');

// Delete a file
$results = $file->write('path\to\your\file');

```

About
=====

Molajo Project has adopted the following:

 * ([Semantic Versioning](http://semver.org/))
 * ([PSR-0 Autoloader Interoperability](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md))
 * ([PSR-1](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md))
 and ([PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md))
 * phpUnit Testing
 * phpDoc


Submitting bugs and feature requests
------------------------------------

Bugs and feature request are tracked on [GitHub](https://github.com/Molajo/Fileservices/issues)

Author
------

Amy Stephen - <AmyStephen@gmail.com> - <http://twitter.com/AmyStephen><br />
See also the list of [contributors](https://github.com/Molajo/Fileservices/contributors) participating in this project.

License
-------

**Molajo Filesystem** is licensed under the MIT License - see the `LICENSE` file for details

Acknowledgements
----------------

[W3C File API: Directories and System] W3C Working Draft 17 April 2012 → http://www.w3.org/TR/file-system-api/
specifications were followed, as closely as possible.

More Information
----------------
- [Usage](doc/usage.md)
- [Extend](doc/extend.md)
- [Specifications](doc/specifications.md)
