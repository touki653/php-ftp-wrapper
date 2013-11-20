<?php

namespace Touki\FTP\Tests;

use Touki\FTP\Factory\FilesystemFactory;
use Touki\FTP\Factory\PermissionsFactory;
use Touki\FTP\Model\Directory;
use Touki\FTP\FilesystemFetcher;

/**
 * Filesystem fetcher scenario test
 * Let test the fetcher in real conditions
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class FilesystemFetcherScenarioTest extends ConnectionAwareTestCase
{
    public function setUp()
    {
        parent::setUp();

        $wrapper = self::$wrapper;
        $factory = new FilesystemFactory(new PermissionsFactory);

        $this->fetcher = new FilesystemFetcher($wrapper, $factory);
    }

    public function provideRootDirectories()
    {
        return array(
            array('/'),
            array(''),
            array(new Directory('/')),
            array(new Directory('')),
        );
    }

    public function provideFolderDirectories()
    {
        return array(
            array('/folder'),
            array('folder'),
            array(new Directory('/folder')),
            array(new Directory('folder')),
        );
    }

    public function provideRootDirectoriesInstances()
    {
        return array(
            array(new Directory('/')),
            array(new Directory('')),
        );
    }

    public function provideFolderDirectoriesInstances()
    {
        return array(
            array(new Directory('/folder')),
            array(new Directory('folder')),
        );
    }

    /**
     * @dataProvider provideRootDirectories
     */
    public function testFindAllInRootDirectory($dirs)
    {
        $list = $this->fetcher->findAll($dirs);

        $this->assertCount(3, $list);

        $this->assertInstanceOf('Touki\FTP\Model\File', $list[0]);
        $this->assertEquals('/file1.txt', $list[0]->getRealpath());

        $this->assertInstanceOf('Touki\FTP\Model\File', $list[1]);
        $this->assertEquals('/file2.txt', $list[1]->getRealpath());

        $this->assertInstanceOf('Touki\FTP\Model\Directory', $list[2]);
        $this->assertEquals('/folder', $list[2]->getRealpath());
    }

    /**
     * @dataProvider provideFolderDirectories
     */
    public function testFindAllInFolderDirectory($dirs)
    {
        $list = $this->fetcher->findAll($dirs);

        $this->assertCount(2, $list);

        $this->assertInstanceOf('Touki\FTP\Model\File', $list[0]);
        $this->assertEquals('/folder/file3.txt', $list[0]->getRealpath());

        $this->assertInstanceOf('Touki\FTP\Model\Directory', $list[1]);
        $this->assertEquals('/folder/subfolder', $list[1]->getRealpath());
    }

    /**
     * @dataProvider provideRootDirectories
     */
    public function testFindFilesInRootDirectory($dirs)
    {
        $list = $this->fetcher->findFiles($dirs);

        $this->assertCount(2, $list);

        $this->assertInstanceOf('Touki\FTP\Model\File', $list[0]);
        $this->assertEquals('/file1.txt', $list[0]->getRealpath());

        $this->assertInstanceOf('Touki\FTP\Model\File', $list[1]);
        $this->assertEquals('/file2.txt', $list[1]->getRealpath());
    }

    /**
     * @dataProvider provideFolderDirectories
     */
    public function testFindFilesInFolderDirectory($dirs)
    {
        $list = $this->fetcher->findFiles($dirs);

        $this->assertCount(1, $list);

        $this->assertInstanceOf('Touki\FTP\Model\File', $list[0]);
        $this->assertEquals('/folder/file3.txt', $list[0]->getRealpath());
    }

    /**
     * @dataProvider provideRootDirectories
     */
    public function testFindDirectoriesInRootDirectory($dirs)
    {
        $list = $this->fetcher->findDirectories($dirs);

        $this->assertCount(1, $list);

        $this->assertInstanceOf('Touki\FTP\Model\Directory', $list[0]);
        $this->assertEquals('/folder', $list[0]->getRealpath());
    }

    /**
     * @dataProvider provideFolderDirectories
     */
    public function testFindDirectoriesInFolderDirectory($dirs)
    {
        $list = $this->fetcher->findDirectories($dirs);

        $this->assertCount(1, $list);

        $this->assertInstanceOf('Touki\FTP\Model\Directory', $list[0]);
        $this->assertEquals('/folder/subfolder', $list[0]->getRealpath());
    }

    /**
     * @expectedException        Touki\FTP\Exception\NoResultException
     * @expectedExceptionMessage Filesystem /unknownfilesystem not found
     */
    public function testFindFilesystemByNameNotFoundThrowsException()
    {
        $this->fetcher->findFilesystemByName("/unknownfilesystem");
    }

    public function testFindFilesystemByNameFindingFile1()
    {
        $filesystem = $this->fetcher->findFilesystemByName('/file1.txt');

        $this->assertInstanceOf('Touki\FTP\Model\File', $filesystem);
        $this->assertEquals('/file1.txt', $filesystem->getRealpath());
    }


    /**
     * @dataProvider provideRootDirectoriesInstances
     */
    public function testFindFilesystemByNameFindingFile1WithDirectories($dirs)
    {
        $filesystem = $this->fetcher->findFilesystemByName('/file1.txt', $dirs);

        $this->assertInstanceOf('Touki\FTP\Model\File', $filesystem);
        $this->assertEquals('/file1.txt', $filesystem->getRealpath());
    }

    /**
     * @dataProvider provideRootDirectoriesInstances
     */
    public function testFindFilesystemByNameFindingFolder($dirs)
    {
        $filesystem = $this->fetcher->findFilesystemByName('/folder', $dirs);

        $this->assertInstanceOf('Touki\FTP\Model\Directory', $filesystem);
        $this->assertEquals('/folder', $filesystem->getRealpath());
    }

    public function testFindFilesystemByNameFindingFile3()
    {
        $filesystem = $this->fetcher->findFilesystemByName('folder/file3.txt');

        $this->assertInstanceOf('Touki\FTP\Model\File', $filesystem);
        $this->assertEquals('/folder/file3.txt', $filesystem->getRealpath());
    }

    /**
     * @dataProvider provideFolderDirectoriesInstances
     */
    public function testFindFilesystemByNameFindingFile3WithDirectory($dirs)
    {
        $filesystem = $this->fetcher->findFilesystemByName('file3.txt', $dirs);

        $this->assertInstanceOf('Touki\FTP\Model\File', $filesystem);
        $this->assertEquals('/folder/file3.txt', $filesystem->getRealpath());
    }

    public function testFindFilesystemByNameFindingSubfolder()
    {
        $filesystem = $this->fetcher->findFilesystemByName('folder/subfolder');

        $this->assertInstanceOf('Touki\FTP\Model\Directory', $filesystem);
        $this->assertEquals('/folder/subfolder', $filesystem->getRealpath());
    }

    /**
     * @dataProvider provideFolderDirectoriesInstances
     */
    public function testFindFilesystemByNameFindingSubfolderWithDirectory($dirs)
    {
        $filesystem = $this->fetcher->findFilesystemByName('subfolder', $dirs);

        $this->assertInstanceOf('Touki\FTP\Model\Directory', $filesystem);
        $this->assertEquals('/folder/subfolder', $filesystem->getRealpath());
    }

    /**
     * @expectedException        Touki\FTP\Exception\NoResultException
     * @expectedExceptionMessage File /unknownfile not found
     */
    public function testFindFileByNameNotFoundThrowsException()
    {
        $this->fetcher->findFileByName("/unknownfile");
    }

    public function testFindFileByNameFindingFile1()
    {
        $filesystem = $this->fetcher->findFileByName('file1.txt');

        $this->assertInstanceOf('Touki\FTP\Model\File', $filesystem);
        $this->assertEquals('/file1.txt', $filesystem->getRealpath());
    }

    /**
     * @dataProvider provideRootDirectoriesInstances
     */
    public function testFindFileByNameFindingFile1WithDirectories($dirs)
    {
        $filesystem = $this->fetcher->findFileByName('/file1.txt', $dirs);

        $this->assertInstanceOf('Touki\FTP\Model\File', $filesystem);
        $this->assertEquals('/file1.txt', $filesystem->getRealpath());
    }

    public function testFindFileByNameFindingFile3()
    {
        $filesystem = $this->fetcher->findFileByName('folder/file3.txt');

        $this->assertInstanceOf('Touki\FTP\Model\File', $filesystem);
        $this->assertEquals('/folder/file3.txt', $filesystem->getRealpath());
    }

    /**
     * @dataProvider provideFolderDirectoriesInstances
     */
    public function testFindFileByNameFindingFile3WithDirectory($dirs)
    {
        $filesystem = $this->fetcher->findFileByName('file3.txt', $dirs);

        $this->assertInstanceOf('Touki\FTP\Model\File', $filesystem);
        $this->assertEquals('/folder/file3.txt', $filesystem->getRealpath());
    }

    /**
     * @expectedException        Touki\FTP\Exception\NoResultException
     * @expectedExceptionMessage Directory /unknowndirectory not found
     */
    public function testFindDirectoryByNameNotFoundThrowsException()
    {
        $this->fetcher->findDirectoryByName("/unknowndirectory");
    }

    public function testFindDirectoryByNameFindingFolder()
    {
        $filesystem = $this->fetcher->findDirectoryByName('folder');

        $this->assertInstanceOf('Touki\FTP\Model\Directory', $filesystem);
        $this->assertEquals('/folder', $filesystem->getRealpath());
    }

    /**
     * @dataProvider provideRootDirectoriesInstances
     */
    public function testFindDirectoryByNameFindingFolderWithDirectories($dirs)
    {
        $filesystem = $this->fetcher->findDirectoryByName('/folder', $dirs);

        $this->assertInstanceOf('Touki\FTP\Model\Directory', $filesystem);
        $this->assertEquals('/folder', $filesystem->getRealpath());
    }

    public function testFindDirectoryByNameFindingSubfolder()
    {
        $filesystem = $this->fetcher->findDirectoryByName('folder/subfolder');

        $this->assertInstanceOf('Touki\FTP\Model\Directory', $filesystem);
        $this->assertEquals('/folder/subfolder', $filesystem->getRealpath());
    }

    /**
     * @dataProvider provideFolderDirectoriesInstances
     */
    public function testFindDirectoryByNameFindingSubfolderWithDirectories($dirs)
    {
        $filesystem = $this->fetcher->findDirectoryByName('subfolder', $dirs);

        $this->assertInstanceOf('Touki\FTP\Model\Directory', $filesystem);
        $this->assertEquals('/folder/subfolder', $filesystem->getRealpath());
    }

    public function testGetCwdReturnsCorrect()
    {
        $filesystem = $this->fetcher->getCwd();

        $this->assertInstanceOf('Touki\FTP\Model\Directory', $filesystem);
        $this->assertEquals('/', $filesystem->getRealpath());

        self::$wrapper->chdir('/folder');

        $filesystem = $this->fetcher->getCwd();

        $this->assertInstanceOf('Touki\FTP\Model\Directory', $filesystem);
        $this->assertEquals('/folder', $filesystem->getRealpath());

        self::$wrapper->chdir('/');
    }
}
