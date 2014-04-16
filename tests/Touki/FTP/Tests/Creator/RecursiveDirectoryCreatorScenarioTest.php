<?php

namespace Touki\FTP\Tests\Creator;

use Touki\FTP\FilesystemFetcher;
use Touki\FTP\Model\Directory;
use Touki\FTP\Tests\ConnectionAwareTestCase;
use Touki\FTP\Creator\RecursiveDirectoryCreator;
use Touki\FTP\Factory\FilesystemFactory as Factory;
use Touki\FTP\Factory\PermissionsFactory;

/**
 * Recursive directory creator scenario test case
 * Tests the RecursiveDirectoryCreator in a real scenario
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class RecursiveDirectoryCreatorScenarioTest extends ConnectionAwareTestCase
{
    public function setUp()
    {
        parent::setUp();

        $factory = new Factory(new PermissionsFactory);
        $this->fetcher = new FilesystemFetcher(self::$wrapper, $factory);
    }

    public function testProcessCreatesAllDirectories()
    {
        $wrapper = self::$wrapper;

        $directory = new Directory("/foo");
        $creator = new RecursiveDirectoryCreator($directory);
        $creator->execute($wrapper, $this->fetcher);

        $this->assertTrue($wrapper->chdir('/foo'));
        $wrapper->rmdir('/foo');
        $wrapper->chdir('/');

        $directory = new Directory('/foo/deep1');
        $creator = new RecursiveDirectoryCreator($directory);
        $creator->execute($wrapper, $this->fetcher);

        $this->assertTrue($wrapper->chdir('/foo/deep1'));
        $wrapper->rmdir('/foo/deep1');
        $wrapper->rmdir('/foo');
        $wrapper->chdir('/');

        $directory = new Directory('/foo/deep1/deep2');
        $creator = new RecursiveDirectoryCreator($directory);
        $creator->execute($wrapper, $this->fetcher);

        $this->assertTrue($wrapper->chdir('/foo/deep1/deep2'));
        $wrapper->rmdir('/foo/deep1/deep2');
        $wrapper->rmdir('/foo/deep1');
        $wrapper->rmdir('/foo');
        $wrapper->chdir('/');

        $directory = new Directory("/folder");
        $creator = new RecursiveDirectoryCreator($directory);
        $creator->execute($wrapper, $this->fetcher);

        $this->assertTrue($wrapper->chdir('/folder'));
        $wrapper->chdir('/');

        $directory = new Directory("/folder/nonexistant");
        $creator = new RecursiveDirectoryCreator($directory);
        $creator->execute($wrapper, $this->fetcher);

        $this->assertTrue($wrapper->chdir('/folder/nonexistant'));
        $wrapper->rmdir('/folder/nonexistant');
        $wrapper->chdir('/');
    }
}
