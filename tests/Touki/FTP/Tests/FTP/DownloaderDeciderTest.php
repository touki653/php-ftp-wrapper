<?php

namespace Touki\FTP\Tests\FTP;

use Touki\FTP\FTP;
use Touki\FTP\FTPWrapper;
use Touki\FTP\FTP\DownloaderDecider;

/**
 * Downloader Factory (Decider) Test case
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class DownloaderDeciderTest extends \PHPUnit_Framework_TestCase
{
    protected $decider;

    public function setUp()
    {
        $wrapper = $this->getMockBuilder('Touki\FTP\FTPWrapper')->disableOriginalConstructor()->getMock();
        $this->decider = new DownloaderDecider($wrapper);
    }

    public function testDecideResourceDownloader()
    {
        $options = array(
            FTP::NON_BLOCKING  => false,
            FTP::TRANSFER_MODE => FTPWrapper::ASCII,
            FTP::START_POS     => 0
        );
        $local = fopen(__FILE__, 'r');

        $downloader = $this->decider->decide($local, $options);

        $this->assertInstanceOf('Touki\FTP\FTP\Downloader\ResourceDownloader', $downloader);
        $this->assertSame($downloader->getMode(), FTPWrapper::ASCII);
        $this->assertSame($downloader->getStartPos(), 0);

        fclose($local);
    }

    public function testDecideFileDownloader()
    {
        $options = array(
            FTP::NON_BLOCKING  => false,
            FTP::TRANSFER_MODE => FTPWrapper::ASCII,
            FTP::START_POS     => 10
        );

        $downloader = $this->decider->decide(__FILE__, $options);

        $this->assertInstanceOf('Touki\FTP\FTP\Downloader\FileDownloader', $downloader);
        $this->assertSame($downloader->getMode(), FTPWrapper::ASCII);
        $this->assertSame($downloader->getStartPos(), 10);
    }

    public function testDecideNonBlockingResourceDownloader()
    {
        $options = array(
            FTP::NON_BLOCKING  => true,
            FTP::TRANSFER_MODE => FTPWrapper::BINARY,
            FTP::START_POS     => 15
        );
        $local = fopen(__FILE__, 'r');

        $downloader = $this->decider->decide($local, $options);

        $this->assertInstanceOf('Touki\FTP\FTP\Downloader\NbResourceDownloader', $downloader);
        $this->assertSame($downloader->getMode(), FTPWrapper::BINARY);
        $this->assertSame($downloader->getStartPos(), 15);

        fclose($local);
    }

    public function testDecideNonBlockingFileDownloader()
    {
        $options = array(
            FTP::NON_BLOCKING  => true,
            FTP::NON_BLOCKING_CALLBACK  => function() { return "foo"; },
            FTP::TRANSFER_MODE => FTPWrapper::ASCII,
            FTP::START_POS     => 10
        );

        $downloader = $this->decider->decide(__FILE__, $options);

        $this->assertInstanceOf('Touki\FTP\FTP\Downloader\NbFileDownloader', $downloader);
        $this->assertSame($downloader->getMode(), FTPWrapper::ASCII);
        $this->assertSame($downloader->getStartPos(), 10);
        $this->assertSame($downloader->getCallback(), $options[ FTP::NON_BLOCKING_CALLBACK ]);
    }
}
