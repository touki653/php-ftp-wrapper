<?php

namespace Touki\FTP\Tests\FTP\Uploader;

use Touki\FTP\FTPWrapper;
use Touki\FTP\FTP\Uploader\NbFileUploader;
use Touki\FTP\Tests\ConnectionAwareTestCase;

/**
 * Non blocking File uploader Test case
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class NbFileUploaderTest extends ConnectionAwareTestCase
{
    public function setUp()
    {
        parent::setUp();

        $callback       = function() {};
        $connection     = self::$connection;
        $this->ftp      = new FTPWrapper($connection);
        $this->uploader = new NbFileUploader($this->ftp, $callback, FTPWrapper::BINARY, 0);
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage File /local/path/to/foo does not exist
     */
    public function testUploadNonExistantFile()
    {
        $this->uploader->upload('/remote/file', '/local/path/to/foo');
    }

    public function testUploadSuccessful()
    {
        $localFile  = __FILE__;
        $remoteFile = basename(__FILE__);
        $called     = false;
        $callback   = function () use (&$called) {
            $called = true;
        };
        $this->uploader->setCallback($callback);

        $this->assertTrue($this->uploader->upload($remoteFile, $localFile));
        $this->assertGreaterThan(-1, $this->ftp->size($remoteFile), 'Upload failed');
        $this->assertTrue($called);

        $this->ftp->delete($remoteFile);
    }

    public function testUploadSuccessfulOnDeepFolder()
    {
        $localFile  = __FILE__;
        $remoteFile = sprintf("/folder/%s", basename(__FILE__));
        $called     = false;
        $callback   = function () use (&$called) {
            $called = true;
        };
        $this->uploader->setCallback($callback);

        $this->assertTrue($this->uploader->upload($remoteFile, $localFile));
        $this->assertGreaterThan(-1, $this->ftp->size($remoteFile), 'Upload failed');
        $this->assertTrue($called);

        $this->ftp->delete($remoteFile);
    }
}
