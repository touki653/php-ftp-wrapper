<?php

namespace Touki\FTP\Tests;

use Touki\FTP\FTP;
use Touki\FTP\DownloaderVoter;
use Touki\FTP\Model\File;

/**
 * Downloader voter test case
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class DownloaderVoterTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $mock = $this->getMock('Touki\FTP\DownloaderVotableInterface');
        $mock
            ->expects($this->once())
            ->method('vote')
            ->will($this->returnValue(false))
        ;
        $this->voter = new DownloaderVoter;
        $this->voter->addVotable($mock);
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Could not resolve a downloader with the given options
     */
    public function testVoteNoEligibleVoters()
    {
        $this->voter->vote('foo', new File('bar'), array('baz'));
    }

    public function testVoteElectFileDownloader()
    {
        $mock = $this->getMock('Touki\FTP\DownloaderVotableInterface');
        $mock
            ->expects($this->once())
            ->method('vote')
            ->will($this->returnValue(true))
        ;

        $this->voter->addVotable($mock);
        $votable = $this->voter->vote('foo', new File('bar'), array('baz'));

        $this->assertInstanceOf('Touki\FTP\DownloaderVotableInterface', $votable);
    }
}
