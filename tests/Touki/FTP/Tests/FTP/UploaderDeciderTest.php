<?php

namespace Touki\FTP\Tests\FTP;

use Touki\FTP\FTP;
use Touki\FTP\FTPWrapper;
use Touki\FTP\FTP\UploaderDecider;

/**
 * Uploader Factory (Decider) Test case
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class UploaderDeciderTest extends \PHPUnit_Framework_TestCase
{
    protected $decider;

    public function setUp()
    {
        $wrapper = $this->getMockBuilder('Touki\FTP\FTPWrapper')->disableOriginalConstructor()->getMock();
        $this->decider = new UploaderDecider($wrapper);
    }

    public function testDecideResourceUploader()
    {
        $options = array(
            FTP::NON_BLOCKING  => false,
            FTP::TRANSFER_MODE => FTPWrapper::ASCII,
            FTP::START_POS     => 0
        );
        $local = fopen(__FILE__, 'r');

        $uploader = $this->decider->decide($local, $options);

        $this->assertInstanceOf('Touki\FTP\FTP\Uploader\ResourceUploader', $uploader);
        $this->assertSame($uploader->getMode(), FTPWrapper::ASCII);
        $this->assertSame($uploader->getStartPos(), 0);
    }

    public function testDecideFileUploader()
    {
        $options = array(
            FTP::NON_BLOCKING  => false,
            FTP::TRANSFER_MODE => FTPWrapper::ASCII,
            FTP::START_POS     => 10
        );

        $uploader = $this->decider->decide(__FILE__, $options);

        $this->assertInstanceOf('Touki\FTP\FTP\Uploader\FileUploader', $uploader);
        $this->assertSame($uploader->getMode(), FTPWrapper::ASCII);
        $this->assertSame($uploader->getStartPos(), 10);
    }

    public function testDecideNonBlockingResourceUploader()
    {
        $options = array(
            FTP::NON_BLOCKING  => true,
            FTP::TRANSFER_MODE => FTPWrapper::BINARY,
            FTP::START_POS     => 15
        );
        $local = fopen(__FILE__, 'r');

        $uploader = $this->decider->decide($local, $options);

        $this->assertInstanceOf('Touki\FTP\FTP\Uploader\NbResourceUploader', $uploader);
        $this->assertSame($uploader->getMode(), FTPWrapper::BINARY);
        $this->assertSame($uploader->getStartPos(), 15);
    }

    public function testDecideNonBlockingFileUploader()
    {
        $options = array(
            FTP::NON_BLOCKING  => true,
            FTP::NON_BLOCKING_CALLBACK  => function() { return "foo"; },
            FTP::TRANSFER_MODE => FTPWrapper::ASCII,
            FTP::START_POS     => 10
        );

        $uploader = $this->decider->decide(__FILE__, $options);

        $this->assertInstanceOf('Touki\FTP\FTP\Uploader\NbFileUploader', $uploader);
        $this->assertSame($uploader->getMode(), FTPWrapper::ASCII);
        $this->assertSame($uploader->getStartPos(), 10);
        $this->assertSame($uploader->getCallback(), $options[ FTP::NON_BLOCKING_CALLBACK ]);
    }
}
