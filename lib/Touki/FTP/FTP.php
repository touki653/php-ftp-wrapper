<?php

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
class FTP
{
    const NON_BLOCKING          = 1;
    const NON_BLOCKING_CALLBACK = 2;
    const TRANSFER_MODE         = 3;
    const START_POS             = 4;

    /**
     * FTP Wrapper
     * @var FTPWrapper
     */
    protected $ftp;

    /**
     * Directory Walker
     * @var FTPFilesystemManager
     */
    protected $walker;

    /**
     * Constructor
     *
     * @param FTPWrapper           $ftp    The FTP Wrapper
     * @param FTPFilesystemManager $walker Directory Walker
     */
    public function __construct(FTPWrapper $ftp, FTPFilesystemManager $walker)
    {
        $this->ftp    = $ftp;
        $this->walker = $walker;
    }

    /**
     * {@inheritDoc}
     */
    public function findFilesystems(Directory $directory = null)
    {
        $directory = $directory ?: new Directory('/');

        return $this->walker->findAll($directory);
    }

    /**
     * {@inheritDoc}
     */
    public function findFiles(Directory $directory = null)
    {
        $directory = $directory ?: new Directory('/');

        return $this->walker->findFiles($directory);
    }

    /**
     * {@inheritDoc}
     */
    public function findDirectories(Directory $directory = null)
    {
        $directory = $directory ?: new Directory('/');

        return $this->walker->findDirectories($directory);
    }

    /**
     * {@inheritDoc}
     */
    public function fileExists(File $file)
    {
        try {
            return null !== $this->walker->findFileByFile($file);
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
            return null !== $this->walker->findDirectoryByDirectory($directory);
        } catch (DirectoryException $e) {
            return false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function findFileByName($filename)
    {
        return $this->walker->findFileByName($filename);
    }

    /**
     * {@inheritDoc}
     */
    public function findDirectoryByName($directory)
    {
        return $this->walker->findDirectoryByName($directory);
    }

    /**
     * {@inheritDoc}
     */
    public function download($local, Filesystem $remote, array $options = array())
    {
    }
}
