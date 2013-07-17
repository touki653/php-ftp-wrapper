<?php

namespace Touki\FTP\Tests\Manager;

use Touki\FTP\Manager\FTPFilesystemManager;
use Touki\FTP\PermissionsFactory;
use Touki\FTP\FilesystemFactory;
use Touki\FTP\FTPWrapper;
use Touki\FTP\Model\File;
use Touki\FTP\Model\Directory;
use Touki\FTP\Tests\ConnectionAwareTestCase;

/**
 * FTP Filesystem Manager Test Case
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class FTPFilesystemManagerTest extends ConnectionAwareTestCase
{
    public function setUp()
    {
        parent::setUp();

        $wrapper = new FTPWrapper(self::$connection);
        $factory = new FilesystemFactory(new PermissionsFactory);
        $this->manager = new FTPFilesystemManager($wrapper, $factory);
    }

    public function testFindAll()
    {
        $list = $this->manager->findAll("/");
        $this->assertCount(3, $list);

        foreach ($list as $item) {
            $this->assertInstanceOf('Touki\FTP\Model\Filesystem', $item);
        }
    }

    public function testFindFiles()
    {
        $list = $this->manager->findFiles("/");
        $this->assertcount(2, $list);

        foreach ($list as $item) {
            $this->assertInstanceOf('Touki\FTP\Model\File', $item);
        }
    }

    public function testFindDirectories()
    {
        $list = $this->manager->findDirectories("/");

        $this->assertCount(1, $list);
        $this->assertInstanceOf('Touki\FTP\Model\Directory', $list[0]);
        $this->assertEquals("/folder", $list[0]->getRealpath());
    }

    public function testFindFileByName()
    {
        $file = $this->manager->findFileByName("file1.txt");

        $this->assertInstanceOf('Touki\FTP\Model\File', $file);
        $this->assertEquals('/file1.txt', $file->getRealpath());
    }

    public function testFindFileByNameInFolder()
    {
        $file = $this->manager->findFileByName("/folder/file3.txt");

        $this->assertInstanceOf('Touki\FTP\Model\File', $file);
        $this->assertEquals('/folder/file3.txt', $file->getRealpath());
    }

    public function testFindFileByNameNotDirectory()
    {
        $file = $this->manager->findFileByName("/folder");

        $this->assertNull($file);
    }

    public function testFindFileByFileFound()
    {
        $file    = new File("folder/file3.txt");
        $fetched = $this->manager->findFileByFile($file);

        $this->assertInstanceOf('Touki\FTP\Model\File', $fetched);
        $this->assertEquals('/folder/file3.txt', $fetched->getRealpath());
    }

    public function testFindFileByFileDirectory()
    {
        $this->assertNull($this->manager->findFileByFile(new File('/folder')));
    }

    public function testFindFileByFileNotFound()
    {
        $this->assertNull($this->manager->findFileByFile(new File('/foo.txt')));
    }

    public function testFindDirectoryByName()
    {
        $dir = $this->manager->findDirectoryByName("folder");

        $this->assertInstanceOf('Touki\FTP\Model\Directory', $dir);
        $this->assertEquals('/folder', $dir->getRealpath());
    }

    public function testFindDirectoryByNameFileGiven()
    {
        $this->assertNull($this->manager->findDirectoryByName("file1.txt"));
    }

    public function testFindDirectoryByNameDeepFolder()
    {
        $dir = $this->manager->findDirectoryByName('folder/subfolder');

        $this->assertInstanceOf('Touki\FTP\Model\Directory', $dir);
        $this->assertEquals('/folder/subfolder', $dir->getRealpath());
    }

    public function testFindDirectoryByDirectory()
    {
        $dir = $this->manager->findDirectoryByDirectory(new Directory("folder"));

        $this->assertInstanceOf('Touki\FTP\Model\Directory', $dir);
        $this->assertEquals('/folder', $dir->getRealpath());
    }

    public function testFindDirectoryByDirectoryFileGiven()
    {
        $this->assertNull($this->manager->findDirectoryByDirectory(new Directory("file1.txt")));
    }

    public function testFindDirectoryByDirectoryDeepFolder()
    {
        $dir = $this->manager->findDirectoryByDirectory(new Directory('folder/subfolder'));

        $this->assertInstanceOf('Touki\FTP\Model\Directory', $dir);
        $this->assertEquals('/folder/subfolder', $dir->getRealpath());
    }
}
