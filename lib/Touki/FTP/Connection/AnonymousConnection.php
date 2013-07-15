<?php

namespace Touki\FTP\Connection;

use Touki\FTP\ConnectionInterface;

/**
 * Anonymous connection
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class AnonymousConnection extends Connection
{
    /**
     * Constructor
     *
     * @param string  $host    FTP Server adress
     * @param integer $port    Port to connect to
     * @param integer $timeout Default timeout
     */
    public function __construct($host, $port = 21, $timeout = 90)
    {
        parent::__construct($host, 'anonymous', '', $port, $timeout);
    }

    /**
     * Overrides username
     *
     * @return string 'Anonymous'
     */
    public function getUsername()
    {
        return 'anonymous';
    }

    /**
     * Overrides password
     *
     * @return string ''
     */
    public function getPassword()
    {
        return '';
    }
}
