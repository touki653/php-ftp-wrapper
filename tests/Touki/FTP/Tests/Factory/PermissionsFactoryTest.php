<?php

namespace Touki\FTP\Tests\Factory;

use Touki\FTP\Factory\PermissionsFactory;
use Touki\FTP\Model\Permissions;

/**
 * Permissions factory test case
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class PermissionsFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->factory = new PermissionsFactory;
    }

    /**
     * @expectedException        Touki\FTP\Exception\ParseException
     * @expectedExceptionMessage foobaz is not a valid permission input
     */
    public function testBuildOnInvalidLengthCountThrowsException()
    {
        $this->factory->build('foobaz');
    }

    public function provideBuildParams()
    {
        $x = Permissions::EXECUTABLE;
        $w = Permissions::WRITABLE;
        $r = Permissions::READABLE;

        return array(
            array('---' , 0,            false, false, false),
            array('--x' , $x,           false, false, true),
            array('-w-' , $w,           false, true,  false),
            array('-wx' , $w | $x,      false, true,  true),
            array('r--' , $r,           true,  false, false),
            array('r-x' , $r | $x,      true,  false, true),
            array('rw-' , $r | $w ,     true,  true,  false),
            array('rwx' , $r | $w | $x, true,  true,  true),
        );
    }

    /**
     * @dataProvider provideBuildParams
     */
    public function testBuildGivesExpectedFlags($entry, $expected, $readable, $writable, $executable)
    {
        $perm = $this->factory->build($entry);

        $this->assertInstanceOf('Touki\FTP\Model\Permissions', $perm);
        $this->assertSame($expected, $perm->getFlags());
        $this->assertSame($readable, $perm->isReadable());
        $this->assertSame($writable, $perm->isWritable());
        $this->assertSame($executable, $perm->isExecutable());
    }
}
