<?php

namespace Touki\FTP\FTP\Downloader;

/**
 * Resource Downloader
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class ResourceDownloader extends AbstractDownloader
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

        $this->ftp->pasv(true);

        return $this->ftp->fget($local, $remoteFile, $this->mode, $this->startPos);
    }
}
