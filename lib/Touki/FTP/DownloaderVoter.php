<?php

namespace Touki\FTP;

use Touki\FTP\Downloader\FTP as FTPDownloader;
use Touki\FTP\Model\Filesystem;

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
     * @param boolean                    $prepend Whether to prepend the votable
     */
    public function addVotable(DownloaderVotableInterface $votable, $prepend = false)
    {
        if ($prepend) {
            array_unshift($this->votables, $votable);
        } else {
            $this->votables[] = $votable;
        }
    }

    /**
     * Adds the default downloaders
     *
     * @param FTPWrapper $wrapper An FTP Wrapper
     */
    public function addDefaultFTPDownloaders(FTPWrapper $wrapper)
    {
        $this->addVotable(new FTPDownloader\FileDownloader($wrapper));
    }

    /**
     * Processes the vote
     *
     * @param  mixed               $local   Local component
     * @param  Filesystem          $remote  Remote component
     * @param  array               $options Voter's options
     * @return DownloaderInterface Chosen Downloader
     */
    public function vote($local, Filesystem $remote, array $options = array())
    {
        foreach ($this->votables as $votable) {
            if (true === $votable->vote($local, $remote, $options)) {
                return $votable;
            }
        }

        throw new \InvalidArgumentException("Could not resolve a downloader with the given options");
    }
}
