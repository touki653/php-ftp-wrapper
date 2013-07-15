<?php

namespace Touki\FTP\Tests\FTP\Uploader;

use Touki\FTP\FTPWrapper;
use Touki\FTP\FTP\Uploader\NbResourceUploader;
use Touki\FTP\Tests\ConnectionAwareTestCase;

/**
 * Non blocking Resource uploader Test case
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class NbResourceUploaderTest extends ConnectionAwareTestCase
{
    public function setUp()
    {
        parent::setUp();

        $callback       = function() {};
        $connection     = self::$connection;
        $this->ftp      = new FTPWrapper($connection);
        $this->uploader = new NbResourceUploader($this->ftp, $callback, FTPWrapper::BINARY, 0);
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Invalid local resource given. Expected resource, got string
     */
    public function testUploadNonResourceArgument()
    {
        $this->uploader->upload('/remote/file', '/local/path/to/foo');
    }

    public function testUploadSuccessful()
    {
        $localFile  = fopen(__FILE__, 'r');
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
        fclose($localFile);
    }

    public function testUploadSuccessfulOnDeepFolder()
    {
        $localFile  = fopen(__FILE__, 'r');
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
        fclose($localFile);
    }
}
