<?php

require __DIR__.'/../../vendor/autoload.php';

use Touki\FTP\ConnectionContext;
use Touki\FTP\Connector\SSLConnector;

$connector = new SSLConnector;
$parameters = new ConnectionContext("localhost", "ftp-tests", "ftp-tests", 22);

$connection = $connector->open($parameters);

var_dump($connection); // Object(Touki\FTP\Connection)

$connector->close($connection);
