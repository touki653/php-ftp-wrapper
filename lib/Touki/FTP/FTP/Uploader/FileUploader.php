<?php

namespace Touki\FTP\FTP\Uploader;

/**
 * File uploader
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class FileUploader extends AbstractUploader
{
    /**
     * {@inheritDoc}
     *
     * @throws InvalidArgumentException When $local does not exist
     * @throws InvalidArgumentException When $local is not readable
     */
    public function upload($remoteFile, $local)
    {
        if (!file_exists($local)) {
            throw new \InvalidArgumentException(sprintf("File %s does not exist", $local));
        }

        if (!is_readable($local)) {
            throw new \InvalidArgumentException(sprintf("File %s is not readable", $local));
        }

        return $this->ftp->put($remoteFile, $local, $this->mode, $this->startPos);
    }
}
