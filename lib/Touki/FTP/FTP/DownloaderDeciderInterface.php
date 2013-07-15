<?php

namespace Touki\FTP\FTP;

/**
 * Interface class to decide which downloader to use
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
interface DownloaderDeciderInterface
{
    /**
     * Decides which downloader to use
     *
     * @param  string              $local   Local file or resource
     * @param  array               $options Options
     * @return DownloaderInterface An Downloader
     */
    public function decide($local, array $options = array());
}
