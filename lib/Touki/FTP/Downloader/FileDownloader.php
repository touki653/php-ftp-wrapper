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
 * FTP File downloader
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class FileDownloader implements CommandInterface
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
     * @param string  $local     Local filename
     * @param File    $file      Remote file
     * @param integer $mode      Transfert mode
     * @param integer $resumepos Resume position
     */
    public function __construct($local, File $file, $mode = FTP_BINARY, $resumepos = 0)
    {
        $this->local = $local;
        $this->file  = $file;
        $this->mode  = $mode;
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

        if (!$wrapper->get($this->local, $this->file->getRealPath(), $this->mode, $this->resumepos)) {
            throw new DownloadFailedException(sprintf("Failed to download file %s", $this->file->getRealPath()));
        }
    }
}
