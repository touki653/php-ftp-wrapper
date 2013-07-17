<?php

namespace Touki\FTP\Connection;

/**
 * SSL Connection
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class SSLConnection extends Connection
{
    /**
     * {@inheritDoc}
     */
    protected function doConnect()
    {
        return @ftp_ssl_connect($this->getHost(), $this->getPort(), $this->getTimeout());
    }
}
