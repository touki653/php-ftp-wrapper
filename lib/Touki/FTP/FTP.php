<?php

/**
 * This file is a part of the FTP Wrapper package
 *
 * For the full informations, please read the README file
 * distributed with this source code
 *
 * @package FTP Wrapper
 * @version 1.0.0
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
    const NON_BLOCKING          = 1;
    const NON_BLOCKING_CALLBACK = 2;
    const TRANSFER_MODE         = 3;
    const START_POS             = 4;

    /**
     * Filesystem manager
     * @var FTPFilesystemManager
     */
    protected $manager;

    /**
     * Downloader Voter
     * @var DownloaderVoterInterface
     */
    protected $dlVoter;

    /**
     * Uploader Voter
     * @var UploaderVoterInterface
     */
    protected $ulVoter;

    /**
     * Constructor
     *
     * @param FTPFilesystemManager $manager Directory manager
     */
    public function __construct(FTPFilesystemManager $manager, DownloaderVoterInterface $dlVoter, UploaderVoterInterface $ulVoter)
    {
        $this->manager = $manager;
        $this->dlVoter = $dlVoter;
        $this->ulVoter = $ulVoter;
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
     * Get DownloaderVoter
     *
     * @return DownloaderVoterInterface Downloader Voter
     */
    public function getDownloaderVoter()
    {
        return $this->dlVoter;
    }

    /**
     * Set DownloaderVoter
     *
     * @param DownloaderVoterInterface $downloaderVoter Downloader Voter
     */
    public function setDownloaderVoter(DownloaderVoterInterface $downloaderVoter)
    {
        $this->dlVoter = $downloaderVoter;
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
    public function filesystemExists(Filesystem $fs)
    {
        try {
            return null !== $this->manager->findFilesystemByFilesystem($fs);
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
    public function findFilesystemByName($filename)
    {
        return $this->manager->findFilesystemByName($filename);
    }

    /**
     * {@inheritDoc}
     */
    public function findFileByName($filename)
    {
        return $this->manager->findFileByName($filename);
    }

    /**
     * {@inheritDoc}
     */
    public function findDirectoryByName($directory)
    {
        return $this->manager->findDirectoryByName($directory);
    }

    /**
     * {@inheritDoc}
     */
    public function getCwd()
    {
        return $this->manager->getCwd();
    }

    /**
     * {@inheritDoc}
     */
    public function download($local, Filesystem $remote, array $options = array())
    {
        if (!$this->filesystemExists($remote)) {
            throw new DirectoryException(sprintf(
                "Remote filesystem %s of type %s does not exists",
                $remote->getRealpath(),
                get_class($remote)
            ));
        }

        $options = $options + array(
            FTP::NON_BLOCKING => false
        );
        $downloader = $this->dlVoter->vote($local, $remote, $options);

        return $downloader->download($local, $remote, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function upload(Filesystem $remote, $local, array $options = array())
    {
        $options = $options + array(
            FTP::NON_BLOCKING => false
        );
        $uploader = $this->ulVoter->vote($remote, $local, $options);

        return $upload->upload($remote, $local, $options);
    }
}
