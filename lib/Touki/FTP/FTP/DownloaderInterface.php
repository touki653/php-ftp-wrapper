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
     * @param  string  $remoteFile Remote File
     * @param  mixed   $local      Local file or resource
     * @return boolean TRUE on success
     */
    public function download($remoteFile, $local);
}
