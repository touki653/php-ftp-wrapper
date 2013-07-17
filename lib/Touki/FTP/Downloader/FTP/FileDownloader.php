<?php

namespace Touki\FTP\Downloader\FTP;

use Touki\FTP\FTP;
use Touki\FTP\FTPWrapper;
use Touki\FTP\DownloaderInterface;
use Touki\FTP\DownloaderVotableInterface;
use Touki\FTP\Model\Filesystem;
use Touki\FTP\Model\File;

/**
 * FTP File downloader
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class FileDownloader implements DownloaderInterface, DownloaderVotableInterface
{
    /**
     * FTP Wrapper
     * @var FTPWrapper
     */
    protected $wrapper;

    /**
     * Constructor
     *
     * @param FTPWrapper $wrapper An FTPWrapper instance
     */
    public function __construct(FTPWrapper $wrapper)
    {
        $this->wrapper = $wrapper;
    }

    /**
     * {@inheritDoc}
     */
    public function vote($local, Filesystem $remote, array $options = array())
    {
        return
            ($remote instanceof File)
            && false === is_resource($local)
            && false === is_dir($local)
            && isset($options[ FTP::NON_BLOCKING ])
            && false === $options[ FTP::NON_BLOCKING ]
        ;
    }

    /**
     * {@inheritDoc}
     *
     * @throws InvalidArgumentException When argument(s) is(are) incorrect
     */
    public function download($local, Filesystem $remote, array $options = array())
    {
        // Double check
        if (!$this->vote($local, $remote, $options)) {
            throw new \InvalidArgumentException("Download arguments given do not match with the downloader");
        }

        $defaults = array(
            FTP::TRANSFER_MODE => FTPWrapper::BINARY,
            FTP::START_POS     => 0
        );
        $options = $options + $defaults;

        $this->wrapper->pasv(true);

        return $this->wrapper->get($local, $remote->getRealPath(), $options[ FTP::TRANSFER_MODE ], $options[ FTP::START_POS ]);
    }
}
