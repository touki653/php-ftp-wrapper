<?php

namespace Touki\FTP\Tests\FTP\Downloader;

use Touki\FTP\FTPWrapper;
use Touki\FTP\FTP\Downloader\NbResourceDownloader;
use Touki\FTP\Tests\ConnectionAwareTestCase;

/**
 * Non blocking Resource downloader Test case
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class NbResourceDownloaderTest extends ConnectionAwareTestCase
{
    public function setUp()
    {
        parent::setUp();

        $callback         = function() {};
        $connection       = self::$connection;
        $this->ftp        = new FTPWrapper($connection);
        $this->downloader = new NbResourceDownloader($this->ftp, $callback, FTPWrapper::BINARY, 0);
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Invalid local resource given. Expected resource, got string
     */
    public function testDownloadNonResourceArgument()
    {
        $this->downloader->download('/remote/file', '/local/path/to/foo');
    }

    public function testDownloadSuccessful()
    {
        $filename   = tempnam(sys_get_temp_dir(), 'ftp-test');
        $localFile  = fopen($filename, 'w+');
        $remoteFile = 'file1.txt';
        $called     = false;
        $callback   = function () use (&$called) {
            $called = true;
        };
        $this->downloader->setCallback($callback);

        $this->assertTrue($this->downloader->download($localFile, $remoteFile));
        $this->assertFileExists($filename);
        $this->assertTrue($called, 'Callback has not been called');

        fclose($localFile);
        unlink($filename);
    }

    public function testDownloadSuccessfulOnDeepFolder()
    {
        $filename   = tempnam(sys_get_temp_dir(), 'ftp-test');
        $localFile  = fopen($filename, 'w+');
        $remoteFile = '/folder/file3.txt';
        $called     = false;
        $callback   = function () use (&$called) {
            $called = true;
        };
        $this->downloader->setCallback($callback);

        $this->assertTrue($this->downloader->download($localFile, $remoteFile));
        $this->assertFileExists($filename);
        $this->assertTrue($called);

        fclose($localFile);
        unlink($filename);
    }
}
