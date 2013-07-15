<?php

namespace Touki\FTP\FTP;

use Touki\FTP\FTP;
use Touki\FTP\FTPWrapper;
use Touki\FTP\FTP\Downloader\ResourceDownloader;
use Touki\FTP\FTP\Downloader\NbResourceDownloader;
use Touki\FTP\FTP\Downloader\FileDownloader;
use Touki\FTP\FTP\Downloader\NbFileDownloader;

/**
 * Factory class for downloader
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class DownloaderDecider implements DownloaderDeciderInterface
{
    /**
     * FTP Wrapper
     * @var FTPWrapper
     */
    protected $ftp;

    /**
     * Constructor
     *
     * @param FTPWrapper $ftp An FTPWrapper instance
     */
    public function __construct(FTPWrapper $ftp)
    {
        $this->ftp = $ftp;
    }

    /**
     * {@inheritDoc}
     */
    public function decide($local, array $options = array())
    {
        $defaults = array(
            FTP::NON_BLOCKING          => false,
            FTP::NON_BLOCKING_CALLBACK => function() {},
            FTP::TRANSFER_MODE         => FTPWrapper::BINARY,
            FTP::START_POS             => 0
        );
        $options = $options + $defaults;

        if (false === $options[ FTP::NON_BLOCKING ]) {
            if (is_resource($local)) {
                return new ResourceDownloader(
                    $this->ftp,
                    $options[ FTP::TRANSFER_MODE ],
                    $options[ FTP::START_POS ]
                );
            } else {
                return new FileDownloader(
                    $this->ftp,
                    $options[ FTP::TRANSFER_MODE ],
                    $options[ FTP::START_POS ]
                );
            }
        } else {
            if (is_resource($local)) {
                return new NbResourceDownloader(
                    $this->ftp,
                    $options[ FTP::NON_BLOCKING_CALLBACK ],
                    $options[ FTP::TRANSFER_MODE ],
                    $options[ FTP::START_POS ]
                );
            } else {
                return new NbFileDownloader(
                    $this->ftp,
                    $options[ FTP::NON_BLOCKING_CALLBACK ],
                    $options[ FTP::TRANSFER_MODE ],
                    $options[ FTP::START_POS ]
                );
            }
        }
    }
}
