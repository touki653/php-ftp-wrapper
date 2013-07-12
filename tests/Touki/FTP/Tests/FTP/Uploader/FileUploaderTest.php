<?php

namespace Touki\FTP\Tests\FTP\Uploader;

use Touki\FTP\FTPWrapper;
use Touki\FTP\FTP\Uploader\FileUploader;
use Touki\FTP\Tests\ConnectionAwareTestCase;

/**
 * File uploader Test case
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class FileUploaderTest extends ConnectionAwareTestCase
{
    public function setUp()
    {
        parent::setUp();

        $connection     = self::$connection;
        $this->ftp      = new FTPWrapper($connection);
        $this->uploader = new FileUploader($this->ftp, FTPWrapper::BINARY, 0);
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
        $remoteFile = basename(__FILE__);

        $this->assertTrue($this->uploader->upload($remoteFile, __FILE__));
        $this->assertGreaterThan(-1, $this->ftp->size($remoteFile), 'Upload failed');

        $this->ftp->delete($remoteFile);
    }
}
