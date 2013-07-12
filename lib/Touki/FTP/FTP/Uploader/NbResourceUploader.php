<?php

namespace Touki\FTP\FTP\Uploader;

/**
 * Non Blocking Resource Uploader
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class NbResourceUploader extends AbstractNbUploader
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
    }
}
