<?php

namespace Touki\FTP\Tests\Creator;

use Touki\FTP\Creator\RecursiveDirectoryCreator;
use Touki\FTP\Model\Directory;

/**
 * Recursive directory creator test
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class RecursiveDirectoryCreatorTest extends \PHPUnit_Framework_TestCase
{
    public function testProcessOnEmptyDirectoryDoesNothing()
    {
        $wrapper = $this->getMockBuilder('Touki\FTP\FTPWrapper')
            ->disableOriginalConstructor()
            ->getMock();
        $wrapper
            ->expects($this->never())
            ->method('mkdir')
        ;
        $fetcher = $this->getMockBuilder('Touki\FTP\FilesystemFetcher')
            ->disableOriginalConstructor()
            ->getMock();
        $fetcher
            ->expects($this->once())
            ->method('findDirectoryByName')
            ->will($this->returnValue(new Directory('/')))
        ;

        $creator = new RecursiveDirectoryCreator(new Directory);

        $creator->execute($wrapper, $fetcher);
    }

    /**
     * @expectedException        Touki\FTP\Exception\CreationException
     * @expectedExceptionMessage Could not create directory /
     */
    public function testProcessOnNonFolderAndCreationFailedThrowsException()
    {
        $wrapper = $this->getMockBuilder('Touki\FTP\FTPWrapper')
            ->disableOriginalConstructor()
            ->getMock();
        $wrapper
            ->expects($this->once())
            ->method('mkdir')
            ->will($this->returnValue(false))
        ;
        $fetcher = $this->getMockBuilder('Touki\FTP\FilesystemFetcher')
            ->disableOriginalConstructor()
            ->getMock();
        $fetcher
            ->expects($this->once())
            ->method('findDirectoryByName')
            ->will($this->returnValue(null))
        ;

        $creator = new RecursiveDirectoryCreator(new Directory);

        $creator->execute($wrapper, $fetcher);
    }

    public function testProcessOnDeeperFolderWillExecuteForeach()
    {
        $wrapper = $this->getMockBuilder('Touki\FTP\FTPWrapper')
            ->disableOriginalConstructor()
            ->getMock();
        $wrapper
            ->expects($this->exactly(3))
            ->method('mkdir')
            ->will($this->returnValue(true))
        ;
        $fetcher = $this->getMockBuilder('Touki\FTP\FilesystemFetcher')
            ->disableOriginalConstructor()
            ->getMock();
        $fetcher
            ->expects($this->exactly(3))
            ->method('findDirectoryByName')
            ->will($this->returnValue(null))
        ;

        $creator = new RecursiveDirectoryCreator(new Directory('/one/deep/folder'));

        $creator->execute($wrapper, $fetcher);
    }

    /**
     * @expectedException        Touki\FTP\Exception\CreationException
     * @expectedExceptionMessage Could not create directory /one/deep/folder
     */
    public function testProcessOnDeeperFolderWillExecuteForeachWithDifferentBehaviours()
    {
        $wrapper = $this->getMockBuilder('Touki\FTP\FTPWrapper')
            ->disableOriginalConstructor()
            ->getMock();
        $wrapper
            ->expects($this->exactly(2)) // First one is not called since directory is found
            ->method('mkdir')
            ->will($this->onConsecutiveCalls(
                true, // Creation ok
                false // Creation failed -> exception
            ))
        ;
        $fetcher = $this->getMockBuilder('Touki\FTP\FilesystemFetcher')
            ->disableOriginalConstructor()
            ->getMock();
        $fetcher
            ->expects($this->exactly(3))
            ->method('findDirectoryByName')
            ->will($this->onConsecutiveCalls(
                new Directory('/one'), // Found
                null, // Not Found
                null  // Not Found
            ))
        ;

        $creator = new RecursiveDirectoryCreator(new Directory('/one/deep/folder/subfolder'));

        $creator->execute($wrapper, $fetcher);
    }
}
