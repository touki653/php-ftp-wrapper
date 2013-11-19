<?php

namespace Touki\FTP\Connector;

/**
 * Connector for an SSL connection
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class SSLConnector extends Connector
{
    /**
     * {@inheritDoc}
     */
    protected function doConnect($host, $port, $timeout)
    {
        return @ftp_ssl_connect($host, $port, $timeout);
    }
}
