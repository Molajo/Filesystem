**NOT COMPLETE**

=======
Filesystem
=======

General-purpose file and directory services component for PHP applications using adapters to interact with diverse
filesystems in the same way, whether the filesystem is local, an FTP server, or a remote, cloud-based platform.

## System Requirements

* PHP 5.3, or above
* PSR-0 compliant autoloader
* [optional] PHPUnit 3.5+ to execute the test suite (phpunit --version)

## Installation

**Filesystem** is available on Packagist ([Molajo/Filesystem](http://packagist.org/packages/molajo/filesystem)) and
installable via [Composer](http://getcomposer.org/).

If you do not use Composer, you can download the code from ([Github](https://github.com/Molajo/Filesystem)).
Link to ([manual install steps](https://github.com/Molajo/Filesystem)).

## Basic Usage

**Step 1** Establish Filesystem Driver connection

```php
$connect = new \Molajo\Filesystem\Driver();
```
**Step 2** Set properties

```php
$connect->setSourceAdapter('local');
$connect->setSourcePath('/x/y/example.txt');
```
**Step 3** Invoke method.

```php
$data = $connect->read();
```
**Alternatively**

Dependencies can be injected via the constructor:

```php
$options = array (
    'source_adapter' = 'local',
    'source_path' = '/x/y/zebra.txt');

$connect = new \Molajo\Filesystem\Driver($options);
$data = $connect->read();
```
## Basic File and Directory Operations

### Read

To read a specific file from a filesystem:

```php
$options = array (
    'source_adapter' = 'local',
    'source_path' = '/x/y/example.txt');

$connect = new \Molajo\Filesystem\Driver($options);
$data = $connect->read();
```

### List

To list the names of files and/or directories from a filesystem for a given path:

```php
$options = array (
    'source_adapter' = 'local',
    'source_path' = '/x/y/example.txt');

$connect = new \Molajo\Filesystem\Driver($options);
$data = $connect->read();
```

### Write

To write a file to a filesystem:

```php
$options = array (
    'source_adapter' = 'local',
    'source_path' = '/x/y/example.txt');

$connect = new \Molajo\Filesystem\Driver($options);
$data = $connect->read();
```
```

### Copy

To write a file or folder to a specific destination on the filesystem:

```php
$options = array (
    'source_adapter' = 'local',
    'source_path' = '/x/y/example.txt');

$connect = new \Molajo\Filesystem\Driver($options);
$data = $connect->read();
```

### Move

To move a file or folder to a specific destination on the filesystem:

```php
$options = array (
    'source_adapter' = 'local',
    'source_path' = '/x/y/example.txt');

$connect = new \Molajo\Filesystem\Driver($options);
$data = $connect->read();
```

### Delete

To move a file or folder to a specific destination on the filesystem:

```php
$options = array (
    'source_adapter' = 'local',
    'source_path' = '/x/y/example.txt');

$connect = new \Molajo\Filesystem\Driver($options);
$data = $connect->read();
```

## Special Purpose File Operations

### Backup

This shows how to backup a file on one filesystem to another filesystem.

```php
$options = array (
    'source_adapter' = 'local',
    'source_path' = '/x/y/example',
    'target_adapter' = 'ftp',
    'target_path' = '/x/y/backup',
    'archive' = 'zip'
    );

$connect = new \Molajo\Filesystem\Driver($options);
$data = $connect->backup();
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
