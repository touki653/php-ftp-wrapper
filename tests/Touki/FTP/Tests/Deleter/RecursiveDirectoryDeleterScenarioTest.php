<?php

namespace Touki\FTP\Tests\Deleter;

use Touki\FTP\FTPWrapper;
use Touki\FTP\FilesystemFetcher;
use Touki\FTP\Model\File;
use Touki\FTP\Deleter\FileDeleter;
use Touki\FTP\Factory\FilesystemFactory as Factory;
use Touki\FTP\Factory\PermissionsFactory;
use Touki\FTP\Tests\ConnectionAwareTestCase;

/**
 * Recursive directory deleter scenario test case
 * Tests the RecursiveDirectoryDeleter in a real scenario
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class RecursiveDirectoryDeleterScenarioTest extends ConnectionAwareTestCase
{
    public function setUp()
    {
        parent::setUp();

        $factory = new Factory(new PermissionsFactory);
        $this->fetcher = new FilesystemFetcher(self::$wrapper, $factory);
    }

    public function testProcessDeletesTheFile()
    {
        $wrapper = self::$wrapper;
        $send = __FILE__;
    }
}
