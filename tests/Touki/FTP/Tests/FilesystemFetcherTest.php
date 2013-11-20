<?php

namespace Touki\FTP\Tests;

use Touki\FTP\FilesystemFetcher;
use Touki\FTP\Model\Directory;
use Touki\FTP\Model\File;

/**
 * Filesystem fetcher test case
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class FilesystemFetcherTest extends \PHPUnit_Framework_TestCase
{
    private function getWrapperMock()
    {
        $wrapper = $this->getMockBuilder('Touki\FTP\FTPWrapper')
            ->disableOriginalConstructor()
            ->getMock();

        $wrapper
            ->expects($this->once())
            ->method('rawlist')
            ->will($this->returnValue(array(1, 2, 3)))
        ;

        return $wrapper;
    }

    private function getFactoryMock()
    {
        $factory = $this->getMock('Touki\FTP\FilesystemFactoryInterface');
        $factory
            ->expects($this->any())
            ->method('build')
            ->will($this->onConsecutiveCalls(
                new File("/file1"),
                new File("/file2"),
                new Directory("/folder")
            ))
        ;

        return $factory;
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Cannot filter results. Expected callable, got string
     */
    public function testFindByWithWrongCallbackThrowsException()
    {
        $wrapper = $this->getMockBuilder('Touki\FTP\FTPWrapper')
            ->disableOriginalConstructor()
            ->getMock();
        $factory = $this->getMock('Touki\FTP\FilesystemFactoryInterface');

        $fetcher = new FilesystemFetcher($wrapper, $factory);
        $fetcher->findBy('/', 'foo');
    }

    public function provideDirectories()
    {
        return array(
            array('/', '/'),
            array(new Directory('/'), '/'),
            array('/foo', '/foo'),
            array(new Directory('/foo'), '/foo'),
            array('/foo/bar', '/foo/bar'),
            array(new Directory('/foo/bar'), '/foo/bar'),
        );
    }

    /**
     * @dataProvider provideDirectories
     */
    public function testFindByWithFalseReturnedByWrapperRawlistThrowsException($directory, $expectedFolder)
    {
        $this->setExpectedException(
            'Touki\FTP\Exception\DirectoryException',
            sprintf('Directory %s not found', $expectedFolder)
        );

        $wrapper = $this->getMockBuilder('Touki\FTP\FTPWrapper')
            ->disableOriginalConstructor()
            ->getMock();
        $wrapper
            ->expects($this->once())
            ->method('rawlist')
            ->will($this->returnValue(false))
        ;
        $factory = $this->getMock('Touki\FTP\FilesystemFactoryInterface');

        $fetcher = new FilesystemFetcher($wrapper, $factory);
        $fetcher->findBy($directory, function() {});
    }

    public function testFindByWithReturnTrueCallbackReturnsAll()
    {
        $wrapper = $this->getWrapperMock();
        $factory = $this->getFactoryMock();
        $callable = function() {
            return true;
        };

        $fetcher = new FilesystemFetcher($wrapper, $factory);
        $list = $fetcher->findBy('/', $callable);

        $this->assertCount(3, $list);
    }

    public function testFindByWithReturnFalseCallbackReturnsNone()
    {
        $wrapper = $this->getWrapperMock();
        $factory = $this->getFactoryMock();
        $callable = function() {
            return false;
        };

        $fetcher = new FilesystemFetcher($wrapper, $factory);
        $list = $fetcher->findBy('/', $callable);

        $this->assertEmpty($list);
    }

    public function testFindAllReturnsAll()
    {
        $wrapper = $this->getWrapperMock();
        $factory = $this->getFactoryMock();

        $fetcher = new FilesystemFetcher($wrapper, $factory);
        $list = $fetcher->findAll('/');

        $this->assertCount(3, $list);
    }

    public function testFindFilesReturnsFiles()
    {
        $wrapper = $this->getWrapperMock();
        $factory = $this->getFactoryMock();

        $fetcher = new FilesystemFetcher($wrapper, $factory);
        $list = $fetcher->findFiles('/');

        $this->assertCount(2, $list);

        foreach ($list as $item) {
            $this->assertInstanceOf('Touki\FTP\Model\File', $item);
        }
    }

    public function testFindDirectoriesReturnsDirectories()
    {
        $wrapper = $this->getWrapperMock();
        $factory = $this->getFactoryMock();

        $fetcher = new FilesystemFetcher($wrapper, $factory);
        $list = $fetcher->findDirectories('/');

        $this->assertCount(1, $list);

        foreach ($list as $item) {
            $this->assertInstanceOf('Touki\FTP\Model\Directory', $item);
        }
    }

    /**
     * Find One Section
     */
    
    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Cannot filter results. Expected callable, got string
     */
    public function testFindOneByWithWrongCallbackThrowsException()
    {
        $wrapper = $this->getMockBuilder('Touki\FTP\FTPWrapper')
            ->disableOriginalConstructor()
            ->getMock();
        $factory = $this->getMock('Touki\FTP\FilesystemFactoryInterface');

        $fetcher = new FilesystemFetcher($wrapper, $factory);
        $fetcher->findOneBy('/', 'foo');
    }

    /**
     * @dataProvider provideDirectories
     */
    public function testFindOneByWithFalseReturnedByWrapperRawlistThrowsException($directory, $expectedFolder)
    {
        $this->setExpectedException(
            'Touki\FTP\Exception\DirectoryException',
            sprintf('Directory %s not found', $expectedFolder)
        );

        $wrapper = $this->getMockBuilder('Touki\FTP\FTPWrapper')
            ->disableOriginalConstructor()
            ->getMock();
        $wrapper
            ->expects($this->once())
            ->method('rawlist')
            ->will($this->returnValue(false))
        ;
        $factory = $this->getMock('Touki\FTP\FilesystemFactoryInterface');

        $fetcher = new FilesystemFetcher($wrapper, $factory);
        $fetcher->findOneBy($directory, function() {});
    }

    public function testFindOneByWithReturnTrueCallableReturnsFirst()
    {
        $wrapper = $this->getWrapperMock();
        $file    = new File('/file1');
        $factory = $this->getMock('Touki\FTP\FilesystemFactoryInterface');
        $factory
            ->expects($this->once())
            ->method('build')
            ->will($this->returnValue($file))
        ;
        $callable = function() {
            return true;
        };

        $fetcher = new FilesystemFetcher($wrapper, $factory);
        $find = $fetcher->findOneBy('/', $callable);

        $this->assertSame($file, $find);
    }

    /**
     * @expectedException        Touki\FTP\Exception\NoResultException
     * @expectedExceptionMessage No result were found
     */
    public function testFindOneByWithReturnFalseCallableThrowsException()
    {
        $wrapper = $this->getWrapperMock();
        $factory = $this->getFactoryMock();
        $callable = function() {
            return false;
        };

        $fetcher = new FilesystemFetcher($wrapper, $factory);
        $find = $fetcher->findOneBy('/', $callable);

        $this->assertNull($find);
    }
}
