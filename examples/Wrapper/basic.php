<?php

require __DIR__.'/../../vendor/autoload.php';

use Touki\FTP\ConnectionContext;
use Touki\FTP\FTPWrapper;
use Touki\FTP\Connector\Connector;

$connector = new Connector;
$parameters = new ConnectionContext("localhost", "ftp-tests", "ftp-tests");

$connection = $connector->open($parameters);

$wrapper = new FTPWrapper($connection);

var_dump($wrapper->nlist('/'));

$connector->close($connection);
