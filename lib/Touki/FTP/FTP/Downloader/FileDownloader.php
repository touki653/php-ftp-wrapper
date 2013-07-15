<?php

namespace Touki\FTP\FTP\Downloader;

/**
 * File downloader
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class FileDownloader extends AbstractDownloader
{
    /**
     * {@inheritDoc}
     */
    public function download($local, $remoteFile)
    {
        $this->ftp->pasv(true);

        return $this->ftp->get($local, $remoteFile, $this->mode, $this->startPos);
    }
}
