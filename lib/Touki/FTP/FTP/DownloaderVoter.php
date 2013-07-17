<?php

namespace Touki\FTP\FTP;

use Touki\FTP\FTP\Downloader;

/**
 * Voter class for a given set of options
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class DownloaderVoter
{
    /**
     * An array of votable downloader
     * @var array
     */
    protected $votables = array();

    /**
     * Adds a votable downloader
     *
     * @param DownloaderVotableInterface $votable A votable downloader
     */
    public function addVotable(DownloaderVotableInterface $votable)
    {
        $this->votables[] = $votable;
    }

    public function addDefaultVoters(FTPWrapper $wrapper)
    {
        $this->addVotable(new Downloader\FileDownloader($wrapper));
    }
}
