<?php

namespace Touki\FTP\Tests\FTP;

use Touki\FTP\FTP\DirectoryWalker;
use Touki\FTP\PermissionsFactory;
use Touki\FTP\FileFactory;
use Touki\FTP\FTPWrapper;
use Touki\FTP\Model\File;
use Touki\FTP\Model\Directory;
use Touki\FTP\Tests\ConnectionAwareTestCase;

class DirectoryWalkerTest extends ConnectionAwareTestCase
{
    public function setUp()
    {
        parent::setUp();

        $wrapper = new FTPWrapper(self::$connection);
        $factory = new FileFactory(new PermissionsFactory);
        $this->walker = new DirectoryWalker($wrapper, $factory);
    }

    public function testFindAll()
    {
        $list = $this->walker->findAll("/");
        $this->assertCount(3, $list);

        foreach ($list as $item) {
            $this->assertInstanceOf('Touki\FTP\Model\Filesystem', $item);
        }
    }

    public function testFindFiles()
    {
        $list = $this->walker->findFiles("/");
        $this->assertcount(2, $list);

        foreach ($list as $item) {
            $this->assertInstanceOf('Touki\FTP\Model\File', $item);
        }
    }

    public function testFindDirectories()
    {
        $list = $this->walker->findDirectories("/");

        $this->assertCount(1, $list);
        $this->assertInstanceOf('Touki\FTP\Model\Directory', $list[0]);
        $this->assertEquals("/folder", $list[0]->getRealpath());
    }

    public function testFindFileByName()
    {
        $file = $this->walker->findFileByName("file1.txt");

        $this->assertInstanceOf('Touki\FTP\Model\File', $file);
        $this->assertEquals('/file1.txt', $file->getRealpath());
    }

    public function testFindFileByNameInFolder()
    {
        $file = $this->walker->findFileByName("/folder/file3.txt");

        $this->assertInstanceOf('Touki\FTP\Model\File', $file);
        $this->assertEquals('/folder/file3.txt', $file->getRealpath());
    }

    public function testFindFileByNameNotDirectory()
    {
        $file = $this->walker->findFileByName("/folder");

        $this->assertNull($file);
    }

    public function testFindFileByFileFound()
    {
        $file    = new File("folder/file3.txt");
        $fetched = $this->walker->findFileByFile($file);

        $this->assertInstanceOf('Touki\FTP\Model\File', $fetched);
        $this->assertEquals('/folder/file3.txt', $fetched->getRealpath());
    }

    public function testFindFileByFileDirectory()
    {
        $this->assertNull($this->walker->findFileByFile(new File('/folder')));
    }

    public function testFindFileByFileNotFound()
    {
        $this->assertNull($this->walker->findFileByFile(new File('/foo.txt')));
    }

    public function testFindDirectoryByName()
    {
        $dir = $this->walker->findDirectoryByName("folder");

        $this->assertInstanceOf('Touki\FTP\Model\Directory', $dir);
        $this->assertEquals('/folder', $dir->getRealpath());
    }

    public function testFindDirectoryByNameFileGiven()
    {
        $this->assertNull($this->walker->findDirectoryByName("file1.txt"));
    }

    public function testFindDirectoryByNameDeepFolder()
    {
        $dir = $this->walker->findDirectoryByName('folder/subfolder');

        $this->assertInstanceOf('Touki\FTP\Model\Directory', $dir);
        $this->assertEquals('/folder/subfolder', $dir->getRealpath());
    }

    public function testFindDirectoryByDirectory()
    {
        $dir = $this->walker->findDirectoryByDirectory(new Directory("folder"));

        $this->assertInstanceOf('Touki\FTP\Model\Directory', $dir);
        $this->assertEquals('/folder', $dir->getRealpath());
    }

    public function testFindDirectoryByDirectoryFileGiven()
    {
        $this->assertNull($this->walker->findDirectoryByDirectory(new Directory("file1.txt")));
    }

    public function testFindDirectoryByDirectoryDeepFolder()
    {
        $dir = $this->walker->findDirectoryByDirectory(new Directory('folder/subfolder'));

        $this->assertInstanceOf('Touki\FTP\Model\Directory', $dir);
        $this->assertEquals('/folder/subfolder', $dir->getRealpath());
    }
}
