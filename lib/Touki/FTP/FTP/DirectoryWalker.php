<?php

namespace Touki\FTP\FTP;

use Touki\FTP\FTPWrapper;
use Touki\FTP\FileFactory;
use Touki\FTP\Model\Filesystem;
use Touki\FTP\Model\File;
use Touki\FTP\Model\Directory;

/**
 * Directory Manager to fetch various informations on the distant FTP
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class DirectoryWalker
{
    /**
     * FTP Wrapper
     * @var FTPWrapper
     */
    protected $wrapper;

    /**
     * File factory
     * @var FileFactory
     */
    protected $factory;

    /**
     * Constructor
     *
     * @param FTPWrapper  $wrapper A FTPWrapper instance
     * @param FileFactory $wrapper A FileFactory instance
     */
    public function __construct(FTPWrapper $wrapper, FileFactory $factory)
    {
        $this->wrapper = $wrapper;
        $this->factory = $factory;
    }

    /**
     * Finds all files and directories in the given directory
     *
     * @param  string $directory Directory to traverse
     * @return array
     */
    public function findAll($directory)
    {
        $directory = '/'.ltrim($directory, "/");
        $raw  = $this->wrapper->rawlist($directory);
        $list = array();

        foreach ($raw as $item) {
            $list[] = $this->factory->build($item, $directory);
        }

        return $list;
    }

    /**
     * Filters results with the given callable
     *
     * @param  string   $directory Directory to traverse
     * @param  callable $callable  A Callable filter
     * @return array    Fetched filesystems
     */
    public function findBy($directory, $callable)
    {
        if (!is_callable($callable)) {
            throw new \InvalidArgumentException(sprintf("Cannot filter results. Expected callable, got %s", gettype($callable)));
        }

        $list = $this->findAll($directory);

        return array_values(array_filter($list, $callable));
    }

    /**
     * Finds all files in the given directory
     *
     * @param  string $directory Directory to traverse
     * @return array  An array of File
     */
    public function findFiles($directory)
    {
        return $this->findBy($directory, function($item) {
            return $item instanceof File;
        });
    }

    /**
     * Finds all directories in the given directory
     *
     * @param  string $directory Directory to traverse
     * @return array  An array of Directories
     */
    public function findDirectories($directory)
    {
        return $this->findBy($directory, function($item) {
            return $item instanceof Directory;
        });
    }

    /**
     * Finds a single Directory / file
     *
     * @param  string     $directory Directory to traverse
     * @param  callable   $callable  A fitler callback
     * @return Filesystem Fetched Filesystem
     */
    public function findOneBy($directory, $callable)
    {
        if (!is_callable($callable)) {
            throw new \InvalidArgumentException(sprintf("Cannot filter results. Expected callable, got %s", gettype($callable)));
        }

        $list = $this->findAll($directory);
        $ret  = array_values(array_filter($list, $callable));

        if (count($ret) != 1) {
            return null;
        }

        return $ret[0];
    }

    /**
     * Finds a file matching a given name
     *
     * @param  string $name File path
     * @return File   Fetched file
     */
    public function findFileByName($name)
    {
        $name = '/'.ltrim($name, "/");
        $directory = dirname($name);

        return $this->findOneBy($directory, function($item) use ($name) {
            return $name == $item->getRealpath() && ($item instanceof File);
        });
    }

    /**
     * Finds a file on a given file instance
     *
     * @param  File $file File instance
     * @return File Fetched file
     */
    public function findFileByFile(File $file)
    {
        return $this->findFileByName($file->getRealpath());
    }

    /**
     * Finds a directory on a given name
     *
     * @param  string    $name Directory name
     * @return Directory Fetched directory
     */
    public function findDirectoryByName($name)
    {
        $name = '/'.ltrim($name, '/');
        $directory = dirname($name);

        return $this->findOneBy($directory, function($item) use ($name) {
            return $name == $item->getRealpath() && ($item instanceof Directory);
        });
    }

    /**
     * Finds a directory on a given directory instance
     *
     * @param  Directory $dir Directory instance
     * @return Directory Fetched directory
     */
    public function findDirectoryByDirectory(Directory $dir)
    {
        return $this->findDirectoryByName($dir->getRealpath());
    }
}
