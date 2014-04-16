<?php

namespace Touki\FTP\Tests\Deleter;

use Touki\FTP\Model\File;
use Touki\FTP\Model\Directory;
use Touki\FTP\Deleter\RecursiveDirectoryDeleter;
use Touki\FTP\Exception\NoResultException;

/**
 * Recursive directory deleter test case
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class RecursiveDirectoryDeleterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException        Touki\FTP\Exception\DeletionException
     * @expectedExceptionMessage Couldn't delete file /foo/bar
     */
    public function testExecuteOnNonDeletableFileThrowsException()
    {
        $wrapper = $this->getMockBuilder('Touki\FTP\FTPWrapper')
            ->disableOriginalConstructor()
            ->getMock();
        $wrapper
            ->expects($this->once())
            ->method('delete')
            ->will($this->returnValue(false))
        ;
        $fetcher = $this->getMockBuilder('Touki\FTP\FilesystemFetcher')
            ->disableOriginalConstructor()
            ->getMock();
        $fetcher
            ->expects($this->once())
            ->method('findFiles')
            ->will($this->returnValue(array(new File('/foo/bar'))))
        ;

        $deleter = new RecursiveDirectoryDeleter(new Directory("/foo"));
        $deleter->execute($wrapper, $fetcher);
    }

    /**
     * @expectedException        Touki\FTP\Exception\DeletionException
     * @expectedExceptionMessage Couldn't delete directory /foo
     */
    public function testExecuteOnNonDeletableDirectoryThrowsException()
    {
        $wrapper = $this->getMockBuilder('Touki\FTP\FTPWrapper')
            ->disableOriginalConstructor()
            ->getMock();
        $wrapper
            ->expects($this->never())
            ->method('delete')
        ;
        $wrapper
            ->expects($this->once())
            ->method('rmdir')
            ->will($this->returnValue(false))
        ;
        $fetcher = $this->getMockBuilder('Touki\FTP\FilesystemFetcher')
            ->disableOriginalConstructor()
            ->getMock();
        $fetcher
            ->expects($this->once())
            ->method('findFiles')
            ->will($this->returnValue(array()))
        ;
        $fetcher
            ->expects($this->once())
            ->method('findDirectories')
            ->will($this->returnValue(array()))
        ;

        $deleter = new RecursiveDirectoryDeleter(new Directory("/foo"));
        $deleter->execute($wrapper, $fetcher);
    }

    /**
     * @expectedException        Touki\FTP\Exception\DeletionException
     * @expectedExceptionMessage Couldn't delete directory /foo/bar
     */
    public function testExecuteOnNonDeletableDeepDirectoryThrowsException()
    {
        $wrapper = $this->getMockBuilder('Touki\FTP\FTPWrapper')
            ->disableOriginalConstructor()
            ->getMock();
        $wrapper
            ->expects($this->never())
            ->method('delete')
        ;
        $wrapper
            ->expects($this->once())
            ->method('rmdir')
            ->will($this->returnValue(false))
        ;
        $fetcher = $this->getMockBuilder('Touki\FTP\FilesystemFetcher')
            ->disableOriginalConstructor()
            ->getMock();
        $fetcher
            ->expects($this->any())
            ->method('findFiles')
            ->will($this->returnValue(array()))
        ;
        $fetcher
            ->expects($this->any())
            ->method('findDirectories')
            ->will($this->onConsecutiveCalls(
                array(new Directory('/foo/bar')),
                array()
            ))
        ;

        $deleter = new RecursiveDirectoryDeleter(new Directory("/foo"));
        $deleter->execute($wrapper, $fetcher);
    }
}
