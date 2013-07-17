<?php

namespace Touki\FTP\FTP;

/**
 * Interface for downloader
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
interface DownloaderInterface
{
    /**
     * Processes the download
     *
     * @param  mixed   $local      Local file or resource
     * @param  string  $remoteFile Remote File
     * @return boolean TRUE on success
     */
    public function download($local, $remoteFile);
}
