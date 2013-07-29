<?php

/**
 * This file is a part of the FTP Wrapper package
 *
 * For the full informations, please read the README file
 * distributed with this source code
 *
 * @package FTP Wrapper
 * @version 1.0.1
 * @author  Touki <g.vincendon@vithemis.com>
 */

namespace Touki\FTP\Connection;

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
        parent::__construct($host, 'anonymous', 'guest', $port, $timeout);
    }
}
