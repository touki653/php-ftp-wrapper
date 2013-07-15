<?php

namespace Touki\FTP\Tests\FTP\Uploader;

use Touki\FTP\FTPWrapper;
use Touki\FTP\FTP\Uploader\ResourceUploader;
use Touki\FTP\Tests\ConnectionAwareTestCase;

/**
 * Resource uploader Test case
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class ResourceUploaderTest extends ConnectionAwareTestCase
{
    public function setUp()
    {
        parent::setUp();

        $connection     = self::$connection;
        $this->ftp      = new FTPWrapper($connection);
        $this->uploader = new ResourceUploader($this->ftp, FTPWrapper::BINARY, 0);
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
        $remoteFile = basename(__FILE__);
        $localFile = fopen(__FILE__, 'r');

        $this->assertTrue($this->uploader->upload($remoteFile, $localFile));
        $this->assertGreaterThan(-1, $this->ftp->size($remoteFile), 'Upload failed');

        $this->ftp->delete($remoteFile);
        fclose($localFile);
    }

    public function testUploadSuccessfulOnDeepFolder()
    {
        $remoteFile = sprintf("/folder/%s", basename(__FILE__));
        $localFile = fopen(__FILE__, 'r');

        $this->assertTrue($this->uploader->upload($remoteFile, $localFile));
        $this->assertGreaterThan(-1, $this->ftp->size($remoteFile), 'Upload failed');

        $this->ftp->delete($remoteFile);
        fclose($localFile);
    }
}
