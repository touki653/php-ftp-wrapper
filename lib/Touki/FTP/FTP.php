<?php

namespace Touki\FTP;

use RuntimeException;
use Touki\FTP\FTP\UploaderDecider;
use Touki\FTP\FTP\UploaderDeciderInterface;
use Touki\FTP\FTP\UploaderInterface;
use Touki\FTP\Exception\UploadException;

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
     * Previous Error handler
     * @var mixed
     */
    protected $previousErrorHandler;

    /**
     * Constructor
     *
     * @param FTPWrapper               $ftp             The FTP Wrapper
     * @param UploaderDeciderInterface $uploaderDecider An uploader decider. If none given, a new instance is created
     */
    public function __construct(FTPWrapper $ftp, UploaderDeciderInterface $uploaderDecider = null)
    {
        $this->ftp             = $ftp;
        $this->uploaderDecider = $uploaderDecider ?: new UploaderDecider($ftp);
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
     * Processes the upload
     *
     * @param  UploaderInterface $uploader   An uploader
     * @param  string            $remoteFile Remote file
     * @param  mixed             $local      A local resource or file
     * @return boolean           TRUE on success
     */
    public function doUpload(UploaderInterface $uploader, $remoteFile, $local)
    {
        $this->handleErrors();

        try {
            $result = $uploader->upload($remoteFile, $local);
        } catch (RuntimeException $e) {
            $this->restoreHandler();
            throw new UploadException($e->getMessage(), $e->getCode(), $e);
        }

        $this->restoreHandler();

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function exists($remoteFile)
    {
        return ($this->ftp->size($remoteFile) != -1);
    }

    /**
     * Makes PHP know we want to handle errors
     */
    protected function handleErrors()
    {
        $this->previousErrorHandler = set_error_handler(array($this, 'errorHandler'));
    }

    /**
     * Restores the previous error handler if it had
     */
    protected function restoreHandler()
    {
        if ($this->previousErrorHandler) {
            set_error_handler($this->previousErrorHandler);
        } else {
            restore_error_handler();
        }
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
        throw new RuntimeException($errstr);

        return false;
    }
}
