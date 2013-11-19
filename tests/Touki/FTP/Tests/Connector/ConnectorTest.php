<?php

namespace Touki\FTP\Tests\Connector;

use Touki\FTP\Exception\FTPException;
use Touki\FTP\Connector\Connector;
use Touki\FTP\ConnectionContext;
use Touki\FTP\ConnectionInterface;

/**
 * Connector test case
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class ConnectorTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->connector = new Connector;
    }

    /**
     * @expectedException        Touki\FTP\Exception\ConnectionEstablishedException
     * @expectedExceptionMessage Tried to open an already opened Connection
     */
    public function testOpenOnAlreadyConnectedConnectionThrowsException()
    {
        $context = new ConnectionContext;
        $connection = $this->getMock('Touki\FTP\ConnectionInterface');
        $connection
            ->expects($this->once())
            ->method('isConnected')
            ->will($this->returnValue(true))
        ;

        $this->connector->open($context, $connection);
    }

    /**
     * @expectedException        Touki\FTP\Exception\ConnectionException
     * @expectedExceptionMessage Could not connect to server unknown.host:35
     */
    public function testOpenOnInvalidHostThrowsException()
    {
        $context = new ConnectionContext;
        $context->setHost('unknown.host');
        $context->setPort(35);

        $this->connector->open($context);
    }

    /**
     * We first need to try the credential given in the wrapper to test further contexts
     */
    public function testOpenSuccessful()
    {
        $context = new ConnectionContext;
        $context->setHost(getenv("FTP_HOST"));
        $context->setUsername(getenv("FTP_USERNAME"));
        $context->setPassword(getenv("FTP_PASSWORD"));
        $context->setPort(getenv("FTP_PORT"));

        try {
            $connection = $this->connector->open($context);

            $this->assertInstanceOf('Touki\FTP\ConnectionInterface', $connection);
            $this->assertTrue($connection->isConnected());
            $this->assertNotNull($connection->getStream());
            $this->assertSame($context, $connection->getContext());

            return $connection;
        } catch (FTPException $e) {
            $this->markTestSkipped();
        }
    }

    /**
     * @depends                  testOpenSuccessful
     * @expectedException        Touki\FTP\Exception\ConnectionException
     * @expectedExceptionMessage Could not login using combination of username (foo) and password (***)
     */
    public function testOpenOnInvalidCredentialsThrowsException(ConnectionInterface $connection)
    {
        $context = new ConnectionContext;
        $context->setHost(getenv("FTP_HOST"));
        $context->setUsername('foo');
        $context->setPassword('bar');
        $context->setPort(getenv("FTP_PORT"));

        $this->connector->open($context);
    }

    /**
     * @expectedException        Touki\FTP\Exception\ConnectionUnestablishedException
     * @expectedExceptionMessage Tried to close an unitialized connection
     */
    public function testCloseOnNonOpenedConnectionThrowsException()
    {
        $connection = $this->getMock('Touki\FTP\ConnectionInterface');
        $connection
            ->expects($this->once())
            ->method('isConnected')
            ->will($this->returnValue(false))
        ;

        $this->connector->close($connection);
    }

    /**
     * @depends testOpenSuccessful
     */
    public function testCloseSuccessfulResetsConnectionParams(ConnectionInterface $connection)
    {
        $this->connector->close($connection);

        $this->assertFalse($connection->isConnected());
        $this->assertNull($connection->getContext());
    }
}
