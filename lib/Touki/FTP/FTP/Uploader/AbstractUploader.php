<?php

namespace Touki\FTP\FTP\Uploader;

use Touki\FTP\FTPWrapper;
use Touki\FTP\FTP\UploaderInterface;

/**
 * Base class for standard uploaders
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
abstract class AbstractUploader implements UploaderInterface
{
    /**
     * FTP Wrapper
     * @var FTPWrapper
     */
    protected $ftp;

    /**
     * Transfer Mode
     * @var integer
     */
    protected $mode;

    /**
     * Start pos
     * @var integer
     */
    protected $startPos;

    /**
     * Constructor
     *
     * @param FTPWrapper $ftp      A FTP Wrapper instance
     * @param integer    $mode     Transfer mode
     * @param integer    $startPos The position in the remote file to start uploading to
     */
    public function __construct(FTPWrapper $ftp, $mode, $startPos)
    {
        $this->ftp      = $ftp;
        $this->mode     = $mode;
        $this->startPos = $startPos;
    }

    /**
     * Get Mode
     *
     * @return integer Transfer mode
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * Set Mode
     *
     * @param integer $mode Transfer mode
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
    }

    /**
     * Get StartPos
     *
     * @return integer The position in the remote file to start uploading to
     */
    public function getStartPos()
    {
        return $this->startPos;
    }

    /**
     * Set StartPos
     *
     * @param integer $startPos The position in the remote file to start uploading to
     */
    public function setStartPos($startPos)
    {
        $this->startPos = $startPos;
    }
}
