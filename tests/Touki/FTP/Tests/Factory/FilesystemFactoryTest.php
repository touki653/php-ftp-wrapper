<?php

namespace Touki\FTP\Tests\Factory;

use Touki\FTP\Factory\FilesystemFactory;

/**
 * Filesystem Factory Test Case
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class FilesystemFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->mockPermissions = $this->getMock('Touki\FTP\Model\Permissions');
        $perm = $this->getMock('Touki\FTP\Factory\PermissionsFactory');
        $perm
            ->expects($this->any())
            ->method('build')
            ->will($this->returnValue($this->mockPermissions))
        ;

        $this->factory = new FilesystemFactory($perm);
    }

    /**
     * @expectedException        Touki\FTP\Exception\ParseException
     * @expectedExceptionMessage Could not build a filesystem on given input: foo
     */
    public function testBuildOnInvalidSplitCountThrowsException()
    {
        $this->factory->build('foo');
    }

    public function testBuildDirectoryWithHoursWithoutPrefix()
    {
        $input = "drwxr-x---   3 user  group      4096 Feb 15 12:16 public_ftp";
        $date  = \DateTime::createFromFormat('Y-m-d H:i', sprintf('%s-02-15 12:16', date('Y')));

        $directory = $this->factory->build($input);

        $this->assertInstanceOf('Touki\FTP\Model\Directory', $directory);
        $this->assertEquals('/public_ftp', $directory->getRealpath());
        $this->assertSame($this->mockPermissions, $directory->getOwnerPermissions());
        $this->assertSame($this->mockPermissions, $directory->getGroupPermissions());
        $this->assertSame($this->mockPermissions, $directory->getGuestPermissions());
        $this->assertEquals('user', $directory->getOwner());
        $this->assertEquals('group', $directory->getGroup());
        $this->assertEquals(4096, $directory->getSize());
        $this->assertEquals($date, $directory->getMtime());
    }

    public function testBuildDirectoryWithHoursWithPrefix()
    {
        $input = "drwxr-x---   3 user  group      4096 Feb 15 12:16 public_ftp";
        $date  = \DateTime::createFromFormat('Y-m-d H:i', sprintf('%s-02-15 12:16', date('Y')));

        $directory = $this->factory->build($input, '/foo');

        $this->assertInstanceOf('Touki\FTP\Model\Directory', $directory);
        $this->assertEquals('/foo/public_ftp', $directory->getRealpath());
        $this->assertSame($this->mockPermissions, $directory->getOwnerPermissions());
        $this->assertSame($this->mockPermissions, $directory->getGroupPermissions());
        $this->assertSame($this->mockPermissions, $directory->getGuestPermissions());
        $this->assertEquals('user', $directory->getOwner());
        $this->assertEquals('group', $directory->getGroup());
        $this->assertEquals(4096, $directory->getSize());
        $this->assertEquals($date, $directory->getMtime());
    }

    public function testBuildDirectoryWithoutHoursWithoutPrefix()
    {
        $input = "drwxr-x---   3 user  group      4096 Feb 15 2010 public_ftp";
        $date  = \DateTime::createFromFormat('Y-m-d H:i', '2010-02-15 00:00');

        $directory = $this->factory->build($input);

        $this->assertInstanceOf('Touki\FTP\Model\Directory', $directory);
        $this->assertEquals('/public_ftp', $directory->getRealpath());
        $this->assertSame($this->mockPermissions, $directory->getOwnerPermissions());
        $this->assertSame($this->mockPermissions, $directory->getGroupPermissions());
        $this->assertSame($this->mockPermissions, $directory->getGuestPermissions());
        $this->assertEquals('user', $directory->getOwner());
        $this->assertEquals('group', $directory->getGroup());
        $this->assertEquals(4096, $directory->getSize());
        $this->assertEquals($date, $directory->getMtime());
    }

    public function testBuildDirectoryWithoutHoursWithPrefix()
    {
        $input = "drwxr-x---   3 user  group      4096 Feb 15 2010 public_ftp";
        $date  = \DateTime::createFromFormat('Y-m-d H:i', '2010-02-15 00:00');

        $directory = $this->factory->build($input, '/foo');

        $this->assertInstanceOf('Touki\FTP\Model\Directory', $directory);
        $this->assertEquals('/foo/public_ftp', $directory->getRealpath());
        $this->assertSame($this->mockPermissions, $directory->getOwnerPermissions());
        $this->assertSame($this->mockPermissions, $directory->getGroupPermissions());
        $this->assertSame($this->mockPermissions, $directory->getGuestPermissions());
        $this->assertEquals('user', $directory->getOwner());
        $this->assertEquals('group', $directory->getGroup());
        $this->assertEquals(4096, $directory->getSize());
        $this->assertEquals($date, $directory->getMtime());
    }

    public function testBuildFileWithHoursWithoutPrefix()
    {
        $input = "-rwxr-x---   3 user  group      4096 Feb 15 12:16 public_ftp";
        $date  = \DateTime::createFromFormat('Y-m-d H:i', sprintf('%s-02-15 12:16', date('Y')));

        $file = $this->factory->build($input);

        $this->assertInstanceOf('Touki\FTP\Model\File', $file);
        $this->assertEquals('/public_ftp', $file->getRealpath());
        $this->assertSame($this->mockPermissions, $file->getOwnerPermissions());
        $this->assertSame($this->mockPermissions, $file->getGroupPermissions());
        $this->assertSame($this->mockPermissions, $file->getGuestPermissions());
        $this->assertEquals('user', $file->getOwner());
        $this->assertEquals('group', $file->getGroup());
        $this->assertEquals(4096, $file->getSize());
        $this->assertEquals($date, $file->getMtime());
    }

    public function testBuildFileWithHoursWithPrefix()
    {
        $input = "-rwxr-x---   3 user  group      4096 Feb 15 12:16 public_ftp";
        $date  = \DateTime::createFromFormat('Y-m-d H:i', sprintf('%s-02-15 12:16', date('Y')));

        $file = $this->factory->build($input, '/foo');

        $this->assertInstanceOf('Touki\FTP\Model\File', $file);
        $this->assertEquals('/foo/public_ftp', $file->getRealpath());
        $this->assertSame($this->mockPermissions, $file->getOwnerPermissions());
        $this->assertSame($this->mockPermissions, $file->getGroupPermissions());
        $this->assertSame($this->mockPermissions, $file->getGuestPermissions());
        $this->assertEquals('user', $file->getOwner());
        $this->assertEquals('group', $file->getGroup());
        $this->assertEquals(4096, $file->getSize());
        $this->assertEquals($date, $file->getMtime());
    }

    public function testBuildFileWithoutHoursWithoutPrefix()
    {
        $input = "-rwxr-x---   3 user  group      4096 Feb 15 2010 public_ftp";
        $date  = \DateTime::createFromFormat('Y-m-d H:i', '2010-02-15 00:00');

        $file = $this->factory->build($input);

        $this->assertInstanceOf('Touki\FTP\Model\File', $file);
        $this->assertEquals('/public_ftp', $file->getRealpath());
        $this->assertSame($this->mockPermissions, $file->getOwnerPermissions());
        $this->assertSame($this->mockPermissions, $file->getGroupPermissions());
        $this->assertSame($this->mockPermissions, $file->getGuestPermissions());
        $this->assertEquals('user', $file->getOwner());
        $this->assertEquals('group', $file->getGroup());
        $this->assertEquals(4096, $file->getSize());
        $this->assertEquals($date, $file->getMtime());
    }

    public function testBuildFileWithoutHoursWithPrefix()
    {
        $input = "-rwxr-x---   3 user  group      4096 Feb 15 2010 public_ftp";
        $date  = \DateTime::createFromFormat('Y-m-d H:i', '2010-02-15 00:00');

        $file = $this->factory->build($input, '/foo');

        $this->assertInstanceOf('Touki\FTP\Model\File', $file);
        $this->assertEquals('/foo/public_ftp', $file->getRealpath());
        $this->assertSame($this->mockPermissions, $file->getOwnerPermissions());
        $this->assertSame($this->mockPermissions, $file->getGroupPermissions());
        $this->assertSame($this->mockPermissions, $file->getGuestPermissions());
        $this->assertEquals('user', $file->getOwner());
        $this->assertEquals('group', $file->getGroup());
        $this->assertEquals(4096, $file->getSize());
        $this->assertEquals($date, $file->getMtime());
    }
}
