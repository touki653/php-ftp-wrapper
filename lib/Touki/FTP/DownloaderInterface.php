<?php

namespace Touki\FTP;

use Touki\FTP\Model\Filesystem;

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
     * @param  mixed      $local   Local file, resource, directory
     * @param  Filesystem $remote  Remote File, directory
     * @param  array      $options Downloader options
     * @return boolean    TRUE on success
     */
    public function download($local, Filesystem $remote, array $options = array());
}
