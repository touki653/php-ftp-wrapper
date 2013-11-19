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

namespace Touki\FTP\Downloader;

use Touki\FTP\Model\File;
use Touki\FTP\FTPWrapper;
use Touki\FTP\FilesystemFetcher;
use Touki\FTP\CommandInterface;
use Touki\FTP\Exception\DownloadException;
use Touki\FTP\Exception\DownloadFailedException;

/**
 * Non blocking FTP File downloader
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class NbFileDownloader implements CommandInterface
{
    /**
     * Local filename
     * @var string
     */
    protected $local;

    /**
     * Remote file
     * @var File
     */
    protected $local;

    /**
     * Callback
     * @var callable
     */
    protected $callback;

    /**
     * Transfert mode
     * @var integer
     */
    protected $mode;

    /**
     * Resume position
     * @var integer
     */
    protected $resumepos;

    /**
     * Constructor
     *
     * @param string   $local     Local filename
     * @param File     $file      Remote file
     * @param callable $callback  Callback
     * @param integer  $mode      Transfert mode
     * @param integer  $resumepos Resume position
     */
    public function __construct($local, File $file, $callback = null, $mode = FTP_BINARY, $resumepos = 0)
    {
        $callback = null !== $callback ?: function() {};

        if (!is_callable($callback)) {
            throw new \InvalidArgumentException(sprintf(
                "Argument 3 or NbFileDownloader expected to be callable, got %s",
                gettype($callback)
            ))
        }

        $this->local     = $local;
        $this->callback  = $callback;
        $this->file      = $file;
        $this->mode      = $mode;
        $this->resumepos = $resumepos;
    }

    /**
     * {@inheritDoc}
     */
    public function execute(FTPWrapper $wrapper, FilesystemFetcher $fetcher)
    {
        if (!$fetcher->findFileByName($this->file->getRealpath())) {
            throw new DownloadException(sprintf("File %s does not exist", $this->file->getRealpath()));
        }

        $state = $wrapper->getNb($this->local, $this->file->getRealpath(), $this->mode, $this->resumepos);
        call_user_func_array($this->callback, array($wrapper, $fetcher));

        while (FTPWrapper::MOREDATA === $state) {
            $state = $wrapper->nbContinue();

            call_user_func_array($callback, array($wrapper, $fetcher));
        }

        if (!FTPWrapper::FINISHED === $state) {
            throw new DownloadFailedException(sprintf("Failed to download file %s", $this->file->getRealpath()));
        }
    }
}
