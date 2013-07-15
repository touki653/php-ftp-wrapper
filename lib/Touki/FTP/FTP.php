<?php

namespace Touki\FTP;

use RuntimeException;
use Touki\FTP\FTP\UploaderDecider;
use Touki\FTP\FTP\UploaderDeciderInterface;
use Touki\FTP\FTP\UploaderInterface;
use Touki\FTP\FTP\DownloaderDecider;
use Touki\FTP\FTP\DownloaderDeciderInterface;
use Touki\FTP\FTP\DownloaderInterface;
use Touki\FTP\Exception\UploadException;
use Touki\FTP\Exception\DownloadException;

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
     * FTP Wrapper
     * @var FTPWrapper
     */
    protected $ftp;

    /**
     * Uploader factory
     * @var UploaderDeciderInterface
     */
    protected $uploaderDecider;

    /**
     * Previous Error handler
     * @var mixed
     */
    protected $previousErrorHandler;

    /**
     * Exception class
     * @var \Exception
     */
    protected $exception;

    /**
     * Constructor
     *
     * @param FTPWrapper                 $ftp               The FTP Wrapper
     * @param UploaderDeciderInterface   $uploaderDecider   An uploader decider. If none given, a new instance is created
     * @param DownloaderDeciderInterface $downloaderDecider A downloader decider. If none given, a new instance is created
     */
    public function __construct(
        FTPWrapper $ftp,
        UploaderDeciderInterface $uploaderDecider = null,
        DownloaderDeciderInterface $downloaderDecider = null
    ) {
        $this->ftp               = $ftp;
        $this->uploaderDecider   = $uploaderDecider ?: new UploaderDecider($ftp);
        $this->downloaderDecider = $downloaderDecider ?: new DownloaderDecider($ftp);
        $this->exception         = 'RuntimeException';
    }

    /**
     * {@inheritDoc}
     */
    public function upload($remoteFile, $local, array $options = array())
    {
        $uploader = $this->uploaderDecider->decide($local, $options);

        return $this->doUpload($uploader, $remoteFile, $local);
    }

    /**
     * {@inheritDoc}
     */
    public function download($local, $remoteFile, array $options = array())
    {
        $downloader = $this->downloaderDecider->decide($local, $options);

        return $this->doDownload($downloader, $local, $remoteFile);
    }

    /**
     * {@inheritDoc}
     */
    public function fileExists($remoteFile)
    {
        return ($this->ftp->size($remoteFile) !== -1);
    }

    /**
     * {@inheritDoc}
     */
    public function chdir($directory)
    {
        $this->setException('DirectoryException');
        $this->handleErrors();

        $ret = $this->ftp->chdir($directory);

        $this->restoreHandler();

        return $ret;
    }

    /**
     * {@inheritDoc}
     *
     * No exception are thrown, couldn't find a way to generate one
     */
    public function cdup()
    {
        return $this->ftp->cdup();
    }

    /**
     * Processes the upload
     *
     * @param  UploaderInterface $uploader   An uploader
     * @param  string            $remoteFile Remote file
     * @param  mixed             $local      A local resource or file
     * @return boolean           TRUE on success
     */
    public function doUpload(UploaderInterface $uploader, $remoteFile, $local)
    {
        $this->setException('UploadException');
        $this->handleErrors();

        $result = $uploader->upload($remoteFile, $local);

        $this->restoreHandler();

        return $result;
    }

    /**
     * Processes the download
     *
     * @param  DownloaderInterface $downloader A downloader
     * @param  mixed               $local      A local resource or file
     * @param  string              $remoteFile Remote file
     * @return boolean             TRUE on success
     */
    public function doDownload(DownloaderInterface $downloader, $local, $remoteFile)
    {
        $this->setException('DownloadException');
        $this->handleErrors();

        $result = $downloader->download($local, $remoteFile);

        $this->restoreHandler();

        return $result;
    }

    /**
     * Makes PHP know we want to handle errors
     */
    private function handleErrors()
    {
        $this->previousErrorHandler = set_error_handler(array($this, 'errorHandler'));
    }

    /**
     * Restores the previous error handler if it had
     */
    private function restoreHandler()
    {
        if ($this->previousErrorHandler) {
            set_error_handler($this->previousErrorHandler);
        } else {
            restore_error_handler();
        }

        $this->setException('RuntimeException');
    }

    /**
     * PHP Error handler
     * It converts all warnings to RuntimeException
     *
     * @param  integer $errno   Niveau d'erreur
     * @param  string  $errstr  Message d'erreur
     * @param  string  $errfile Fichier d'erreur
     * @param  integer $errline Ligne d'erreur
     * @return boolean FALSE
     */
    public function errorHandler($errno, $errstr, $errfile, $errline)
    {
        $exception = $this->getException();
        throw new $exception($errstr);

        return false;
    }

    /**
     * Get Exception
     *
     * @return string Exception type to throw
     */
    public function getException()
    {
        return $this->exception;
    }
    
    /**
     * Set Exception
     *
     * @param string $exception Exception type to throw
     */
    public function setException($exception)
    {
        if (!class_exists($exception)) {
            $exception = sprintf("Touki\FTP\Exception\%s", $exception);
        }

        $this->exception = $exception;
    }
}
