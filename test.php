<?php

use Touki\FTP\Connection\Connection;
use Touki\FTP\FTPWrapper;
use Touki\FTP\FTP;

require __DIR__.'/vendor/autoload.php';

// $connection = new StandardConnection("ftp.doral-location.com", "starterre", "godoau69z");
$connection = new Connection("localhost", "ftp-tests", "ftp-tests");
$connection->open();

$wrapper = new FTPWrapper($connection);
$ftp = new FTP($wrapper);

var_dump($ftp->upload(basename(__FILE__), __FILE__));

$connection->close();
