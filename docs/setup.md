# Setting up connection

```php
<?php

use Touki\FTP\Connection\Connection;
use Touki\FTP\Connection\AnonymousConnection;
use Touki\FTP\Connection\SSLConnection;

$connection = new Connection('host', 'user', 'password');
$connection = new AnonymousConnection('host');
$connection = new SSLConnection('host', 'user', 'password');

?>
```

# Setting up the helper

The easiest way to instanciate the main FTP helper is to use its factory

```php
<?php

use Touki\FTP\FTPFactory;

$factory = new FTPFactory;
$ftp     = $factory->build($connection);

?>
```

Or you can instanciate the whole dependency pack to use its components the way you want

```php
<?php

use Touki\FTP\FTP;
use Touki\FTP\FTPWrapper;
use Touki\FTP\FilesystemFactory;
use Touki\FTP\PermissionsFactory;
use Touki\FTP\DownloaderVoter;
use Touki\FTP\UploaderVoter;
use Touki\FTP\Manager\FTPFilesystemManager;

/**
 * The wrapper is a simple class which wraps the base PHP ftp_* functions
 * It needs a Connection instance to get the related stream
 */
$wrapper = new FTPWrapper($connection);

/**
 * This factory creates Permissions models from a given permission string (rw-)
 */
$permFactory = new PermissionsFactory;

/**
 * This factory creates Filesystem models from a given string, ex:
 *     drwxr-x---   3 vincent  vincent      4096 Jul 12 12:16 public_ftp
 *
 * It needs the PermissionsFactory so as to instanciate the given permissions in
 * its model
 */
$fsFactory = new FilesystemFactory($permFactory);

/**
 * This manager focuses on operations on remote files and directories
 * It needs the FTPWrapper so as to do operations on the serveri
 * It needs the FilesystemFfactory so as to create models
 */
$manager = new FTPFilesystemManager($wrapper, $fsFactory);

/**
 * This is the downloader voter. It loads multiple DownloaderVotable class and
 * checks which one is needed on given options
 */
$dlVoter = new DownloaderVoter;

/**
 * Loads up default FTP Downloaders
 * It needs the FTPWrapper to be able to share them with the downloaders
 */
$dlVoter->addDefaultFTPDownloaders($wrapper);

/**
 * This is the uploader voter. It loads multiple UploaderVotable class and
 * checks which one is needed on given options
 */
$ulVoter = new UploaderVoter;

/**
 * Loads up default FTP Uploaders
 * It needs the FTPWrapper to be able to share them with the uploaders
 */
$ulVoter->addDefaultFTPUploaders($wrapper);

/**
 * Finally creates the main FTP
 * It needs the manager to do operations on files
 * It needs the download voter to pick-up the right downloader on ->download
 * It needs the upload voter to pick-up the right uploader on ->upload
 */
return new FTP($manager, $dlVoter, $ulVoter);

?>
```

# Using the simple wrapper

If you just plan to use the simple wrapper, you can instanciate it this way

```php
<?php

use Touki\FTP\Connection\Connection;
use Touki\FTP\FTPWrapper;

$connection = new Connection('host', 'user', 'password');
$connection->open();

$wrapper = new FTPWrapper($connection);

$wrapper->chdir("/folder");
$wrapper->cdup();
$wrapper->get(__DIR__.'/foofile.txt', '/folder/foofile.txt');

$connection->close();

?>

Next step: [Common Usage]

[Common Usage]: https://github.com/touki653/php-ftp-wrapper/blob/master/docs/common_usage.md
