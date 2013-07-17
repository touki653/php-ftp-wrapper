<?php

namespace Touki\FTP\Tests;

use Touki\FTP\FilesystemFactory;
use Touki\FTP\PermissionsFactory;

/**
 * File factory test case
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class FileFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->factory = new FilesystemFactory(new PermissionsFactory);
    }

    public function testBuildDirectory()
    {
        $input = "drwxrwxr-x 8 guillaume guillaume 4096 Jan  26 16:28 .git";
        $dir = $this->factory->build($input, '/');

        $this->assertInstanceOf('Touki\FTP\Model\Directory', $dir);
        $this->assertEquals($dir->getRealpath(), '/.git');
        $this->assertEquals($dir->getOwner(), 'guillaume');
        $this->assertEquals($dir->getGroup(), 'guillaume');
        $this->assertEquals($dir->getSize(), 4096);
        $this->assertEquals($dir->getMtime(), new \DateTime("2013-01-26 16:28:00"));
    }

    public function testBuildFile()
    {
        $input = "-rw-rw-r--  1 guillaume www-data 1035 Jul 16 17:58 phpunit.xml";
        $file = $this->factory->build($input, '/folder');

        $this->assertInstanceOf('Touki\FTP\Model\File', $file);
        $this->assertEquals($file->getRealpath(), '/folder/phpunit.xml');
        $this->assertEquals($file->getOwner(), 'guillaume');
        $this->assertEquals($file->getGroup(), 'www-data');
        $this->assertEquals($file->getSize(), 1035);
        $this->assertEquals($file->getMtime(), new \DateTime("2013-07-16 17:58:00"));
    }
}
