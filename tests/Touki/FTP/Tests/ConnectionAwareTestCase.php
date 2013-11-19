<?php

namespace Touki\FTP\Tests;

use Touki\FTP\FTPWrapper;
use Touki\FTP\ConnectionContext;
use Touki\FTP\Connector\Connector;
use Touki\FTP\Exception\FTPException;

/**
 * Base class for tests which need the connection
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
abstract class ConnectionAwareTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * Static connection
     * @var ConnectionInterface
     */
    protected static $connection;

    /**
     * Static wrapper
     * @var FTPWrapper
     */
    protected static $wrapper;

    /**
     * {@inheritDoc}
     */
    public static function setUpBeforeClass()
    {
        if (null !== self::$connection) {
            return;
        }

        $context = new ConnectionContext;
        $context->setHost(getenv("FTP_HOST"));
        $context->setUsername(getenv("FTP_USERNAME"));
        $context->setPassword(getenv("FTP_PASSWORD"));
        $context->setPort(getenv("FTP_PORT"));

        $connector = new Connector;

        try {
            self::$connection = $connector->open($context);
            self::$wrapper    = new FTPWrapper(self::$connection);
        } catch (FTPException $e) {
        }
    }

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        if (null === self::$connection) {
            $this->markTestSkipped("Could not reliably get a working FTP connection.\nPlease check your phpunit parameters");
        }
    }
}
