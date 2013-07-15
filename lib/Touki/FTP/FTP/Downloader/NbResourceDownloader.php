<?php

namespace Touki\FTP\FTP\Downloader;

use Touki\FTP\FTPWrapper;

/**
 * Non Blocking Resource Downloader
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class NbResourceDownloader extends AbstractNbDownloader
{
    /**
     * {@inheritDoc}
     *
     * @throws InvalidArgumentException When $local is not a resource
     */
    public function download($local, $remoteFile)
    {
        if (!is_resource($local)) {
            throw new \InvalidArgumentException(
                sprintf("Invalid local resource given. Expected resource, got %s", gettype($local))
            );
        }

        $callback = $this->getCallback();
        $this->ftp->pasv(true);

        $state = $this->ftp->fgetNb($local, $remoteFile, $this->mode, $this->startPos);
        call_user_func_array($callback, array());

        while ($state == FTPWrapper::MOREDATA) {
            $state = $this->ftp->nbContinue();

            call_user_func_array($callback, array());
        }

        return $state === FTPWrapper::FINISHED;
    }
}
