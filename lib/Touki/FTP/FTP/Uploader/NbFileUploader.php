<?php

namespace Touki\FTP\FTP\Uploader;

/**
 * Non Blocking File Uploader
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class NbFileUploader extends AbstractNbUploader
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
            throw new \InvalidArgumentException(sprintf("file %s is not readable", $local));
        }
    }
}
