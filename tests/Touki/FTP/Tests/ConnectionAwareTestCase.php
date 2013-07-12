<?php

namespace Touki\FTP\Tests;

use Touki\FTP\Connection\Connection;

/**
 * Base class for connection test case.
 * It creates a pseudo cache of the working connection
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
abstract class ConnectionAwareTestCase extends \PHPUnit_Framework_TestCase
{
    protected static $connection;
    protected static $worked;

    /**
     * Called before class instantiation.
     * It checks first if a connection is already established.
     * If not, a new static variable is assigned to the conncetion
     */
    public static function setUpBeforeClass()
    {
        if (null !== self::$worked) {
            return;
        }

        $connection = new Connection(getenv("FTP_HOST"), getenv("FTP_USERNAME"), getenv("FTP_PASSWORD"), getenv("FTP_PORT"));

        try {
            $connection->open();
            self::$connection = $connection;
            self::$worked = true;
        } catch (\Exception $e) {
            self::$worked = false;
        }
    }

    /**
     * Checks if the connection suceeded.
     * Marks the test skipped if not
     */
    public function setUp()
    {
        if (false === self::$worked) {
            $this->markTestSkipped("Could not reliably get a working FTP connection.\nPlease check your phpunit parameters");
        }
    }
}
