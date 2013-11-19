<?php

namespace Touki\FTP\Tests;

use Touki\FTP\Connection;

/**
 * Connection model Test case
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class ConnectionTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->connection = new Connection;
    }

    /**
     * @expectedException        Touki\FTP\Exception\ConnectionUnestablishedException
     * @expectedExceptionMessage Cannot get stream context, connection is not established
     */
    public function testGetStreamOnNonConnectedThrowsException()
    {
        $this->connection->setConnected(false);

        $this->connection->getStream();
    }
}
