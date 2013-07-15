<?php

namespace Touki\FTP\Tests\FTP\Downloader;

use Touki\FTP\FTPWrapper;
use Touki\FTP\FTP\Downloader\ResourceDownloader;
use Touki\FTP\Tests\ConnectionAwareTestCase;

/**
 * File downloader Test case
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class ResourceDownloaderTest extends ConnectionAwareTestCase
{
    public function setUp()
    {
        parent::setUp();

        $connection       = self::$connection;
        $this->ftp        = new FTPWrapper($connection);
        $this->downloader = new ResourceDownloader($this->ftp, FTPWrapper::BINARY, 0);
    }
    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Invalid local resource given. Expected resource, got string
     */
    public function testDownloadNonResourceArgument()
    {
        $this->downloader->download('/local/path/to/foo', '/remote/file');
    }

    public function testDownloadSuccessful()
    {
        $filename  = tempnam(sys_get_temp_dir(), 'ftp-test');
        $localFile = fopen($filename, 'w+');

        $this->assertTrue($this->downloader->download($localFile, 'file1.txt'));
        $this->assertFileExists($filename);

        fclose($localFile);
        unlink($filename);
    }

    public function testDownloadSuccessfulOnDeepFolder()
    {
        $filename  = tempnam(sys_get_temp_dir(), 'ftp-test');
        $localFile = fopen($filename, 'w+');

        $this->assertTrue($this->downloader->download($localFile, '/folder/file3.txt'));
        $this->assertFileExists($filename);

        fclose($localFile);
        unlink($filename);
    }
}
