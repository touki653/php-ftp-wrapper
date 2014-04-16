<?php

namespace Touki\FTP\Factory;

use Touki\FTP\Model\Filesystem;
use Touki\FTP\Model\File;
use Touki\FTP\Model\Directory;
use Touki\FTP\Model\Symlink;
use Touki\FTP\FilesystemFactoryInterface;
use Touki\FTP\Exception\ParseException;

/**
 * File factory that parses input like
 *   drwxr-x---   3 vincent  vincent      4096 Jul 12 12:16 public_ftp - Directory
 *   -rwxr-x---   3 user     group        4096 Feb 15 12:16 public_ftp - File
 *   -rwxr-x---   3 user     group        4096 Feb 15 2010  public_ftp - Old file (year given)
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class FilesystemFactory implements FilesystemFactoryInterface
{
    /**
     * A Permissions Factory
     * @var PermissionsFactory
     */
    protected $permissionsFactory;

    /**
     * Constructor
     *
     * @param PermissionsFactory $permissionsFactory A Permissions Factory
     */
    public function __construct(PermissionsFactory $permissionsFactory)
    {
        $this->permissionsFactory = $permissionsFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function build($input, $prefix = '')
    {
        $prefix = rtrim($prefix, '/');
        $parts  = preg_split("/\s+/", $input);

        if (count($parts) < 7) {
            throw new ParseException(sprintf("Could not build a filesystem on given input: %s", $input));
        }

        $type       = $parts[0][0];
        $filesystem = $this->resolveFile($type);
        $permParts  = str_split(substr($parts[0], 1, 9), 3);
        $hours      = sscanf($parts[7], "%d:%d");
        $name       = implode(' ', array_slice($parts, 8));
        $year       = date('Y');

        if (null === $hours[1]) {
            $year  = $hours[0];
            $hours = array('00', '00');
        }

        if ($filesystem instanceof Symlink) {
            $exp  = explode(' -> ', $name);
            $name = $exp[0];

            $target = $exp[1];

            // Resolve target path
            $join = array();

            foreach (explode('/', $target) as $component) {
                if (!$component || '.' == $component) {
                    continue;
                }

                if ('..' == $component) {
                    array_pop($join);

                    continue;
                }

                $join[] = $component;
            }

            $filesystem->setTarget(sprintf("%s/%s", $prefix, implode('/', $join)));
        }

        $date = new \DateTime(sprintf("%s-%s-%s %s:%s", $year, $parts[5], $parts[6], $hours[0], $hours[1]));

        $filesystem
            ->setRealpath(sprintf("%s/%s", $prefix, $name))
            ->setOwnerPermissions($this->permissionsFactory->build($permParts[0]))
            ->setGroupPermissions($this->permissionsFactory->build($permParts[1]))
            ->setGuestPermissions($this->permissionsFactory->build($permParts[2]))
            ->setOwner($parts[2])
            ->setGroup($parts[3])
            ->setSize($parts[4])
            ->setMtime($date)
        ;

        return $filesystem;
    }

    /**
     * Resolves the file type
     *
     * @param  string     $type File letter
     * @return Filesystem
     */
    private function resolveFile($type)
    {
        if ('d' == $type) {
            return new Directory;
        } elseif ('l' == $type) {
            return new Symlink;
        } else {
            return new File;
        }
    }
}
