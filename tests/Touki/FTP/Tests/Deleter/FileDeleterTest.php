<?php

namespace Touki\FTP\Tests\Deleter;

use Touki\FTP\Model\File;
use Touki\FTP\Deleter\FileDeleter;
use Touki\FTP\Exception\NoResultException;

/**
 * File deleter test case
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class FileDeleterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException        Touki\FTP\Exception\DeletionException
     * @expectedExceptionMessage Cannot delete file /foo as it doesn't exist
     */
    public function testProcessOnNonExistantFileThrowsException()
    {
        $wrapper = $this->getMockBuilder('Touki\FTP\FTPWrapper')
            ->disableOriginalConstructor()
            ->getMock();
        $wrapper
            ->expects($this->never())
            ->method('delete')
        ;
        $fetcher = $this->getMockBuilder('Touki\FTP\FilesystemFetcher')
            ->disableOriginalConstructor()
            ->getMock();
        $fetcher
            ->expects($this->once())
            ->method('findFileByName')
            ->will($this->throwException(new NoResultException))
        ;

        $deleter = new FileDeleter(new File("/foo"));
        $deleter->execute($wrapper, $fetcher);
    }

    /**
     * @expectedException        Touki\FTP\Exception\DeletionException
     * @expectedExceptionMessage Couldn't delete file /foo
     */
    public function testProcessOnFailedInternalDeletionThrowsException()
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
            ->method('findFileByName')
        ;

        $deleter = new FileDeleter(new File("/foo"));
        $deleter->execute($wrapper, $fetcher);
    }
}
