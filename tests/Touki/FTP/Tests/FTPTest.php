<?php

namespace Touki\FTP\Tests;

use Touki\FTP\FTPWrapper;
use Touki\FTP\FTP;

class FTPTest extends ConnectionAwareTestCase
{
    public function setUp()
    {
        parent::setUp();

        $connection    = self::$connection;
        $this->wrapper = new FTPWrapper($connection);
        $this->ftp     = new FTP($this->wrapper);
    }

    public function testExistsNonExistingFile()
    {
        $this->assertFalse($this->ftp->fileExists('bar.txt'));
        $this->assertFalse($this->ftp->fileExists('folder'));
    }

    public function testExistsExistingFile()
    {
        $this->assertTrue($this->ftp->fileExists('file1.txt'));
    }

    public function testDirectoryExistsFilesGiven()
    {
        $this->assertFalse($this->ftp->directoryExists('file1.txt'));
        $this->assertFalse($this->ftp->directoryExists('folder/file2.txt'));
    }

    public function testDirectoryExistsSuccessful()
    {
        $this->assertTrue($this->ftp->directoryExists('folder'));
    }

    public function testDirectoryExistsOnDeepDirectory()
    {
        $this->assertTrue($this->ftp->directoryExists('folder/subfolder'));
    }

    /**
     * @expectedException Touki\FTP\Exception\UploadException
     * Unknown remote file with a given START_POS leads to a ftp_put error
     */
    public function testUploadErrorsConvertsToException()
    {
        $options = array(
            FTP::START_POS => 20
        );

        $this->ftp->upload('/unknown/remote/file/', __FILE__, $options);
    }

    public function testUploadFileSuccessful()
    {
        $remoteFile = basename(__FILE__);
        $localFile  = __FILE__;

        $this->assertTrue($this->ftp->upload($remoteFile, $localFile));
        $this->assertGreaterThan(-1, $this->wrapper->size($remoteFile));

        $this->wrapper->delete($remoteFile);
    }

    public function testUploadFileResourceSuccessful()
    {
        $remoteFile = basename(__FILE__);
        $filename   = __FILE__;
        $localFile  = fopen($filename, 'r');

        $this->assertTrue($this->ftp->upload($remoteFile, $localFile));
        $this->assertGreaterThan(-1, $this->wrapper->size($remoteFile));

        $this->wrapper->delete($remoteFile);
        fclose($localFile);
    }

    /**
     * @expectedException Touki\FTP\Exception\DownloadException
     * Unknown remote file will generate an error
     */
    public function testDownloadErrorsConvertsToException()
    {
        $localFile = tempnam(sys_get_temp_dir(), 'ftp-test');

        $this->ftp->download($localFile, '/unknown/remote/file/');
    }

    public function testDownloadFileSuccessful()
    {
        $filename   = tempnam(sys_get_temp_dir(), 'ftp-test');
        $localFile  = $filename;
        $remoteFile = 'file1.txt';

        $this->assertTrue($this->ftp->download($localFile, $remoteFile));
        $this->assertFileExists($filename);

        unlink($filename);
    }

    public function testDownloadFileResourceSuccessful()
    {
        $filename   = tempnam(sys_get_temp_dir(), 'ftp-test');
        $localFile  = fopen($filename, 'w+');
        $remoteFile = 'file1.txt';

        $this->assertTrue($this->ftp->download($localFile, $remoteFile));
        $this->assertFileExists($filename);

        unlink($filename);
        fclose($localFile);
    }

    /**
     * @expectedException Touki\FTP\Exception\DirectoryException
     */
    public function testChdirErrorsConvertsToException()
    {
        $this->ftp->chdir("/unknown/remote/dir/");
    }

    public function testChdirSuccessful()
    {
        $this->assertEquals('/', $this->wrapper->pwd());
        $this->ftp->chdir("folder");
        $this->assertEquals("/folder", $this->wrapper->pwd());
        $this->ftp->chdir("/");
    }

    public function testCdupSuccessful()
    {
        $this->wrapper->chdir("/folder");
        $this->assertEquals("/folder", $this->wrapper->pwd());
        $this->ftp->cdup();
        $this->assertEquals("/", $this->wrapper->pwd());
    }

    /**
     * @expectedException Touki\FTP\Exception\DirectoryException
     */
    public function testMkdirErrorsConvertsToException()
    {
        $this->ftp->mkdir("/folder");
    }

    public function testMkdirSuccessful()
    {
        $this->assertTrue($this->ftp->mkdir("/folder2"));

        $this->wrapper->rmdir("/folder2");
    }

    public function testMkdirDeepSuccessful()
    {
        $this->assertTrue($this->ftp->mkdir("/deep/nested/folder"));

        $this->wrapper->rmdir("/deep/nested/folder");
        $this->wrapper->rmdir("/deep/nested");
        $this->wrapper->rmdir("/deep");
    }

}
