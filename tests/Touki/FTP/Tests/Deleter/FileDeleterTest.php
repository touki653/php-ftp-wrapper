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

        $deleter = new FileDeleter(new File("/foo"));
        $deleter->execute($wrapper, $fetcher);
    }

    public function testProcessSuccessDoesNothing()
    {
        $wrapper = $this->getMockBuilder('Touki\FTP\FTPWrapper')
            ->disableOriginalConstructor()
            ->getMock();
        $wrapper
            ->expects($this->once())
            ->method('delete')
            ->will($this->returnValue(true))
        ;
        $fetcher = $this->getMockBuilder('Touki\FTP\FilesystemFetcher')
            ->disableOriginalConstructor()
            ->getMock();

        $deleter = new FileDeleter(new File("/foo"));
        $deleter->execute($wrapper, $fetcher);
    }
}
