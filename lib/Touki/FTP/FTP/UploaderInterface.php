<?php

namespace Touki\FTP\FTP;

/**
 * Interface for uploader
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
interface UploaderInterface
{
    /**
     * Processes the upload
     *
     * @param  string  $remoteFile Remote File
     * @param  mixed   $local      Local file or resource
     * @return boolean TRUE on success
     */
    public function upload($remoteFile, $local);
}
