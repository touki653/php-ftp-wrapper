<?php

require __DIR__.'/../../vendor/autoload.php';

use Touki\FTP\ConnectionContext;
use Touki\FTP\Connector\Connector;

$connector = new Connector;
$parameters = new ConnectionContext("localhost", "ftp-tests", "ftp-tests");

$connection = $connector->open($parameters);

var_dump($connection);                // Object(Touki\FTP\Connection)
var_dump($connection->isConnected()); // true
var_dump($connection->getStream());   // resource (FTP Buffer)
var_dump($connection->getContext());  // Object(Touki\FTP\ConnectionContext)

$connector->close($connection);
