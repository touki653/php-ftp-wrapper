<?php

namespace Touki\FTP\Tests\FTP\Downloader;

use Touki\FTP\FTPWrapper;
use Touki\FTP\FTP\Downloader\FileDownloader;
use Touki\FTP\Tests\ConnectionAwareTestCase;

/**
 * File downloader Test case
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class FileDownloaderTest extends ConnectionAwareTestCase
{
    public function setUp()
    {
        parent::setUp();

        $connection       = self::$connection;
        $this->ftp        = new FTPWrapper($connection);
        $this->downloader = new FileDownloader($this->ftp, FTPWrapper::BINARY, 0);
    }

    public function testDownloadSuccessful()
    {
        $localFile = tempnam(sys_get_temp_dir(), 'ftp-test');

        $this->assertTrue($this->downloader->download($localFile, 'file1.txt'));
        $this->assertFileExists($localFile);

        unlink($localFile);
    }

    public function testDownloadSuccessfulOnDeepFolder()
    {
        $localFile = tempnam(sys_get_temp_dir(), 'ftp-test');

        $this->assertTrue($this->downloader->download($localFile, '/folder/file3.txt'));
        $this->assertFileExists($localFile);

        unlink($localFile);
    }
}
