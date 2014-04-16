<?php

require __DIR__.'/../../vendor/autoload.php';

use Touki\FTP\ConnectionContext;
use Touki\FTP\Connector\Connector;
use Touki\FTP\FTPWrapper;
use Touki\FTP\FilesystemFetcher;
use Touki\FTP\Factory\FilesystemFactory;
use Touki\FTP\Factory\PermissionsFactory;
use Touki\FTP\Factory\WindowsFilesystemFactory;
use Touki\FTP\Model\Directory;

$connector = new Connector;
$parameters = new ConnectionContext("localhost", "ftp-tests", "ftp-tests");
$connection = $connector->open($parameters);
$wrapper = new FTPWrapper($connection);

$factory = new FilesystemFactory(new PermissionsFactory); // Linux
// $factory = new WindowsFilesystemFactory(); // Windows

$fetcher = new FilesystemFetcher($wrapper, $factory);

var_dump($fetcher->findAll('/'));

$connector->close($connection);
