<?php

namespace Touki\FTP\Connector;

use Touki\FTP\Connection;
use Touki\FTP\ConnectionContext;
use Touki\FTP\Exception\ConnectionException;
use Touki\FTP\Exception\ConnectionUnestablishedException;

/**
 * Connector class which opens a new connection on a given context
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class Connector
{
    /**
     * Opens a connection
     *
     * @param ConnectionContext   $context    Connection context
     * @param ConnectionInterface $connection Connection to connect
     *
     * @return ConnectionInterface
     */
    public function open(ConnectionContext $context, ConnectionInterface $connection = null)
    {
        $stream = $this->doConnect($context->getHost(), $context->getPort(), $context->getTimeout());

        if (false === $stream) {
            throw new ConnectionException(sprintf("Could not connect to server %s:%s", $context->getHost(), $context->getPort()));
        }

        if (!@ftp_login($stream, $context->getUsername(), $context->getPassword())) {
            throw new ConnectionException(sprintf(
                "Could not login using combination of username (%s) and password (%s)",
                $context->getUsername(),
                preg_replace("/./", "*", $context->getPassword())
            ));
        }

        $connection = $connection ?: new Connection;
        $connection->setStream($stream);
        $connection->setConnected(true);

        return $connection;
    }

    /**
     * Closes a connection
     *
     * @param ConnectionInterface $connection A Connection
     */
    public function close(ConnectionInterface $connection)
    {
        if (!$connection->isConnected()) {
            throw new ConnectionUnestablishedException("Tried to close an unitialized connection");
        }

        ftp_close($connection->getStream());

        $connection->setStream(null);
        $connection->setConnected(false);
    }

    /**
     * Processes the connection
     *
     * @param string  $host    Host
     * @param integer $port    Port
     * @param integer $timeout Timeout
     *
     * @return resource
     */
    protected function doConnect($host, $port, $timeout)
    {
        return @ftp_connect($host, $port, $timeout);
    }
}
