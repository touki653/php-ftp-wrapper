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
 * File deleter scenario test case
 * Tests the FileDeleter in a real scenario
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class FileDeleterScenarioTest extends ConnectionAwareTestCase
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

        $wrapper->put('/tmp.php', $send, FTPWrapper::BINARY);
        $this->assertTrue(in_array('/tmp.php', $wrapper->nlist('/')));

        $deleter = new FileDeleter(new File('/tmp.php'));
        $deleter->execute($wrapper, $this->fetcher);

        $this->assertFalse(in_array('/tmp.php', $wrapper->nlist('/')));

        $wrapper->put('/folder/tmp.php', $send, FTPWrapper::BINARY);
        $this->assertTrue(in_array('/folder/tmp.php', $wrapper->nlist('/folder')));

        $deleter = new FileDeleter(new File('/folder/tmp.php'));
        $deleter->execute($wrapper, $this->fetcher);

        $this->assertFalse(in_array('/folder/tmp.php', $wrapper->nlist('/folder')));
    }
}
