<?php

namespace Touki\FTP\Tests\Factory;

use Touki\FTP\Factory\WindowsFilesystemFactory;

/**
 * Windows filesystem factory test case
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class WindowsFilesystemFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->factory = new WindowsFilesystemFactory;
    }

    /**
     * @expectedException        Touki\FTP\Exception\ParseException
     * @expectedExceptionMessage Could not build a windows filesystem on given input: foo
     */
    public function testBuildOnInvalidSplitCountThrowsException()
    {
        $this->factory->build('foo');
    }

    public function testBuildFileWithoutPrefix()
    {
        $input = "07-25-13  05:49AM             17919077 dummyfile";
        $date  = \DateTime::createFromFormat("y-m-d H:i", "13-07-25 05:49");
        $filesystem = $this->factory->build($input);

        $this->assertInstanceOf('Touki\FTP\Model\File', $filesystem);
        $this->assertEquals('/dummyfile', $filesystem->getRealpath());
        $this->assertEquals('17919077', $filesystem->getSize());
        $this->assertEquals($date, $filesystem->getMtime());
    }

    public function testBuildFileWithPrefix()
    {
        $input = "07-25-13  05:49AM             17919077 dummyfile";
        $date  = \DateTime::createFromFormat("y-m-d H:i", "13-07-25 05:49");
        $filesystem = $this->factory->build($input, '/foo');

        $this->assertInstanceOf('Touki\FTP\Model\File', $filesystem);
        $this->assertEquals('/foo/dummyfile', $filesystem->getRealpath());
        $this->assertEquals('17919077', $filesystem->getSize());
        $this->assertEquals($date, $filesystem->getMtime());
    }

    public function testBuildDirectoryWithoutPrefix()
    {
        $input = "07-25-13  05:49AM      <DIR>       dummyfolder";
        $date  = \DateTime::createFromFormat("y-m-d H:i", "13-07-25 05:49");
        $filesystem = $this->factory->build($input);

        $this->assertInstanceOf('Touki\FTP\Model\Directory', $filesystem);
        $this->assertEquals('/dummyfolder', $filesystem->getRealpath());
        $this->assertEquals($date, $filesystem->getMtime());
    }

    public function testBuildDirectoryWithPrefix()
    {
        $input = "07-25-13  05:49AM      <DIR>       dummyfolder";
        $date  = \DateTime::createFromFormat("y-m-d H:i", "13-07-25 05:49");
        $filesystem = $this->factory->build($input, '/foo');

        $this->assertInstanceOf('Touki\FTP\Model\Directory', $filesystem);
        $this->assertEquals('/foo/dummyfolder', $filesystem->getRealpath());
        $this->assertEquals($date, $filesystem->getMtime());
    }
}
