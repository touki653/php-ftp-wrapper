<?php

namespace Touki\FTP\Tests\FTP\Downloader;

use Touki\FTP\FTPWrapper;
use Touki\FTP\FTP\Downloader\NbFileDownloader;
use Touki\FTP\Tests\ConnectionAwareTestCase;

/**
 * Non blocking File downloader Test case
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class NbFileDownloaderTest extends ConnectionAwareTestCase
{
    public function setUp()
    {
        parent::setUp();

        $callback         = function() {};
        $connection       = self::$connection;
        $this->ftp        = new FTPWrapper($connection);
        $this->downloader = new NbFileDownloader($this->ftp, $callback, FTPWrapper::BINARY, 0);
    }

    public function testDownloadSuccessful()
    {
        $localFile  = tempnam(sys_get_temp_dir(), 'ftp-test');
        $remoteFile = 'file1.txt';
        $called     = false;
        $callback   = function () use (&$called) {
            $called = true;
        };
        $this->downloader->setCallback($callback);

        $this->assertTrue($this->downloader->download($localFile, $remoteFile));
        $this->assertFileExists($localFile);

        unlink($localFile);
    }

    public function testDownloadSuccessfulOnDeepFolder()
    {
        $localFile  = tempnam(sys_get_temp_dir(), 'ftp-test');
        $remoteFile = '/folder/file3.txt';
        $called     = false;
        $callback   = function () use (&$called) {
            $called = true;
        };
        $this->downloader->setCallback($callback);

        $this->assertTrue($this->downloader->download($localFile, $remoteFile));
        $this->assertFileExists($localFile);

        unlink($localFile);
    }
}
