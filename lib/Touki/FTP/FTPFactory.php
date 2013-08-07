<?php

/**
 * This file is a part of the FTP Wrapper package
 *
 * For the full informations, please read the README file
 * distributed with this source code
 *
 * @package FTP Wrapper
 * @version 1.0.1
 * @author  Touki <g.vincendon@vithemis.com>
 */

namespace Touki\FTP;

use Touki\FTP\Manager\FTPFilesystemManager;

/**
 * Factory class for FTP
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class FTPFactory
{
    protected $wrapper;
    protected $manager;
    protected $dlVoter;
    protected $ulVoter;
    protected $crVoter;

    /**
     * Get Wrapper
     *
     * @return FTPWrapper An FTPWrapper instance
     */
    public function getWrapper()
    {
        return $this->wrapper;
    }

    /**
     * Get Manager
     *
     * @return FTPFilesystemManager A FilesystemManager instance
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * Get DownloaderVoter
     *
     * @return DownloaderVoterInterface A Downloader voter
     */
    public function getDownloaderVoter()
    {
        return $this->dlVoter;
    }

    /**
     * Get UploaderVoter
     *
     * @return UploaderVoterInterface An Uploader voter
     */
    public function getUploaderVoter()
    {
        return $this->uploaderVoter;
    }

    /**
     * Get CreatorVoter
     *
     * @return CreatorVoter A Creator Voter
     */
    public function getCreatorVoter()
    {
        return $this->creatorVoter;
    }

    /**
     * Creates an FTP instance
     *
     * @return FTP An FTP instance
     */
    public function build(ConnectionInterface $connection)
    {
        if (!$connection->isConnected()) {
            $connection->open();
        }

        $this->wrapper = new FTPWrapper($connection);

        $factory = new FilesystemFactory(new PermissionsFactory);
        $this->manager = new FTPFilesystemManager($this->wrapper, $factory);

        $this->dlVoter = new DownloaderVoter;
        $this->dlVoter->addDefaultFTPDownloaders($this->wrapper);

        $this->ulVoter = new UploaderVoter;
        $this->ulVoter->addDefaultFTPUploaders($this->wrapper);

        $this->crVoter = new CreatorVoter;
        $this->crVoter->addDefaultFTPCreators($this->wrapper, $this->manager);

        return new FTP($this->manager, $this->dlVoter, $this->ulVoter, $this->crVoter);
    }
}
