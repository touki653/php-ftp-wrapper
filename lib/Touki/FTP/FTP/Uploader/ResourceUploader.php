<?php

namespace Touki\FTP\FTP\Uploader;

/**
 * Resource Uploader
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class ResourceUploader extends AbstractUploader
{
    /**
     * {@inheritDoc}
     *
     * @throws InvalidArgumentException When $local is not a resource
     */
    public function upload($remoteFile, $local)
    {
        if (!is_resource($local)) {
            throw new \InvalidArgumentException(
                sprintf("Invalid local resource given. Expected resource, got %s", gettype($local))
            );
        }

        $this->ftp->pasv(true);

        return $this->ftp->fput($remoteFile, $local, $this->mode, $this->startpos);
    }
}
