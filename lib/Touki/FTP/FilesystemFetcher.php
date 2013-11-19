<?php

namespace Touki\FTP;

use Touki\FTP\Model\Filesystem;
use Touki\FTP\Model\File;
use Touki\FTP\Model\Directory;
use Touki\FTP\Exception\DirectoryException;

/**
 * FTP Filesystem Manager to fetch various informations on the distant FTP
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class FilesystemFetcher
{
    /**
     * FTP Wrapper
     * @var FTPWrapper
     */
    protected $wrapper;

    /**
     * File factory
     * @var FilesystemFactory
     */
    protected $factory;

    /**
     * Constructor
     *
     * @param FTPWrapper                 $wrapper A FTPWrapper instance
     * @param FilesystemFactoryInterface $factory A FilesystemFactory instance
     */
    public function __construct(FTPWrapper $wrapper, FilesystemFactoryInterface $factory)
    {
        $this->wrapper = $wrapper;
        $this->factory = $factory;
    }

    /**
     * Filters results with the given callable
     *
     * @param  mixed    $directory Directory name or instance to traverse
     * @param  callable $callable  A Callable filter
     * @return array    Fetched filesystems
     *
     * @throws DirectoryException When supplied directory does not exist
     */
    public function findBy($directory, $callable)
    {
        if (!is_callable($callable)) {
            throw new \InvalidArgumentException(sprintf("Cannot filter results. Expected callable, got %s", gettype($callable)));
        }

        if ($directory instanceof Directory) {
            $directory = $directory->getRealpath();
        }

        $directory = '/'.ltrim($directory, '/');
        $raw       = $this->wrapper->rawlist($directory);
        $list      = array();

        if (false === $raw) {
            throw new DirectoryException(sprintf("Directory %s not found", $directory));
        }

        foreach ($raw as $item) {
            $fs = $this->factory->build($item, $directory);

            if (true === call_user_func_array($callable, array($fs))) {
                $list[] = $fs;
            }
        }

        return $list;
    }

    /**
     * Finds all files and directories in the given directory
     *
     * @param  mixed $directory Directory name or a Directory instance
     * @return array
     */
    public function findAll($directory)
    {
        return $this->findBy($directory, function() {
            return true;
        });
    }

    /**
     * Finds all files in the given directory
     *
     * @param  mixed $directory Directory name or Directory instance
     * @return array An array of File
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
     * @param  mixed $directory Directory name or Directory instance
     * @return array An array of Directories
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
     * @param  mixed      $directory Directory name or Directory instance
     * @param  callable   $callable  A fitler callback
     * @return Filesystem Fetched Filesystem
     *
     * @throws DirectoryException When supplied directory does not exist
     */
    public function findOneBy($directory, $callable)
    {
        if (!is_callable($callable)) {
            throw new \InvalidArgumentException(sprintf("Cannot filter results. Expected callable, got %s", gettype($callable)));
        }

        if ($directory instanceof Directory) {
            $directory = $directory->getRealpath();
        }

        $directory = '/'.ltrim($directory, "/");
        $raw       = $this->wrapper->rawlist($directory);

        if (false === $raw) {
            throw new DirectoryException(sprintf("Directory %s not found", $directory));
        }

        foreach ($raw as $item) {
            $fs = $this->factory->build($item, $directory);

            if (true === call_user_func_array($callable, array($fs))) {
                return $fs;
            }
        }

        return null;
    }

    /**
     * Finds a filesystem by its name
     *
     * @param  string         $name        Filesystem name
     * @param  Directory|null $inDirectory Directory to fetch in
     * @return Filesystem
     */
    public function findFilesystemByName($name, Directory $inDirectory = null)
    {
        $name = '/'.ltrim($name, '/');
        $directory = dirname($name);

        if ($inDirectory) {
            $name      = $inDirectory->getRealpath().$name;
            $directory = $inDirectory;
        }

        return $this->findOneBy($directory, function ($item) use ($name) {
            return $name == $item->getRealpath();
        });
    }

    /**
     * Finds a file matching a given name
     *
     * @param  string $name File path
     * @return File   Fetched file
     */
    public function findFileByName($name, Directory $inDirectory = null)
    {
        $name      = '/'.ltrim($name, '/');
        $directory = dirname($name);

        if ($inDirectory) {
            $name      = $inDirectory->getRealpath().$name;
            $directory = $inDirectory;
        }

        return $this->findOneBy($directory, function($item) use ($name) {
            return $name == $item->getRealpath() && ($item instanceof File);
        });
    }

    /**
     * Finds a directory on a given name
     *
     * @param  string    $name Directory name
     * @return Directory Fetched directory
     */
    public function findDirectoryByName($name, Directory $inDirectory = null)
    {
        $name      = '/'.ltrim($name, '/');
        $directory = dirname($name);

        if ($inDirectory) {
            $name      = $inDirectory->getRealpath().$name;
            $directory = $inDirectory;
        }

        return $this->findOneBy($directory, function($item) use ($name) {
            return $name == $item->getRealpath() && ($item instanceof Directory);
        });
    }

    /**
     * Returns the current working directory
     *
     * @return Directory Current directory
     */
    public function getCwd()
    {
        $path = $this->wrapper->pwd();

        return $this->findDirectoryByName($path);
    }
}
