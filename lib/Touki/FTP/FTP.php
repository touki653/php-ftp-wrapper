<?php

/**
 * This file is a part of the FTP Wrapper package
 *
 * For the full informations, please read the README file
 * distributed with this source code
 *
 * @package FTP Wrapper
 * @version 1.1.1
 * @author  Touki <g.vincendon@vithemis.com>
 */

namespace Touki\FTP;

use Touki\FTP\Model\Filesystem;
use Touki\FTP\Model\Directory;
use Touki\FTP\Model\File;
use Touki\FTP\Manager\FTPFilesystemManager;
use Touki\FTP\Exception\DirectoryException;

/**
 * FTP Class which implements standard behaviours of FTP
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class FTP implements FTPInterface
{
    /**
     * Filesystem manager
     * @var FTPFilesystemManager
     */
    protected $manager;

    /**
     * FTP Wrapper
     * @var FTPWrapper
     */
    protected $wrapper;

    /**
     * Constructor
     *
     * @param FTPFilesystemManager $manager Directory manager
     */
    public function __construct(FTPWrapper $wrapper, FTPFilesystemManager $manager)
    {
        $this->wrapper = $wrapper;
        $this->manager = $manager;
    }

    /**
     * Get Manager
     *
     * @return FTPFilesystemManager Filesystem manager
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * {@inheritDoc}
     */
    public function findFilesystems(Directory $directory)
    {
        return $this->manager->findAll($directory);
    }

    /**
     * {@inheritDoc}
     */
    public function findFiles(Directory $directory)
    {
        return $this->manager->findFiles($directory);
    }

    /**
     * {@inheritDoc}
     */
    public function findDirectories(Directory $directory)
    {
        return $this->manager->findDirectories($directory);
    }

    /**
     * {@inheritDoc}
     */
    public function filesystemExists(Filesystem $filesystem)
    {
        try {
            return null !== $this->manager->findFilesystemByFilesystem($filesystem);
        } catch (DirectoryException $e) {
            return false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function fileExists(File $file)
    {
        try {
            return null !== $this->manager->findFileByFile($file);
        } catch (DirectoryException $e) {
            return false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function directoryExists(Directory $directory)
    {
        try {
            return null !== $this->manager->findDirectoryByDirectory($directory);
        } catch (DirectoryException $e) {
            return false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function findFilesystemByName($filename, Directory $inDirectory = null)
    {
        return $this->manager->findFilesystemByName($filename, $inDirectory);
    }

    /**
     * {@inheritDoc}
     */
    public function findFileByName($filename, Directory $inDirectory = null)
    {
        return $this->manager->findFileByName($filename, $inDirectory);
    }

    /**
     * {@inheritDoc}
     */
    public function findDirectoryByName($directory, Directory $inDirectory = null)
    {
        return $this->manager->findDirectoryByName($directory, $inDirectory);
    }

    /**
     * {@inheritDoc}
     */
    public function getCwd()
    {
        return $this->manager->getCwd();
    }
}
