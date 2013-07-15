<?php

namespace Touki\FTP\FTP\Downloader;

use Touki\FTP\FTPWrapper;
use Touki\FTP\FTP\DownloaderInterface;

/**
 * Base class for standard downloaders
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
abstract class AbstractDownloader implements DownloaderInterface
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
     * @param integer    $startPos The position in the remote file to start downloading to
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
     * @return integer The position in the remote file to start downloading to
     */
    public function getStartPos()
    {
        return $this->startPos;
    }

    /**
     * Set StartPos
     *
     * @param integer $startPos The position in the remote file to start downloading to
     */
    public function setStartPos($startPos)
    {
        $this->startPos = $startPos;
    }
}
