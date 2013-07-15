<?php

namespace Touki\FTP\FTP\Downloader;

use Touki\FTP\FTPWrapper;

/**
 * Non Blocking File Downloader
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class NbFileDownloader extends AbstractNbDownloader
{
    /**
     * {@inheritDoc}
     */
    public function download($local, $remoteFile)
    {
        $callback = $this->getCallback();
        $this->ftp->pasv(true);

        $state = $this->ftp->getNb($local, $remoteFile, $this->mode, $this->startPos);
        call_user_func_array($callback, array());

        while ($state == FTPWrapper::MOREDATA) {
            $state = $this->ftp->nbContinue();

            call_user_func_array($callback, array());
        }

        return $state === FTPWrapper::FINISHED;
    }
}
