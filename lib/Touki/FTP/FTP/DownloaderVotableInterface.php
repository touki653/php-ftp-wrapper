<?php

namespace Touki\FTP\FTP;

use Touki\FTP\Model\Filesystem;

/**
 * Interface class to allow a downloader to be chosen
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
interface DownloaderVotableInterface
{
    /**
     * Returns true if given informations matches with the downloader
     *
     * @param  mixed      $local   The local resource/file/directory
     * @param  Filesystem $remote  Remote file/directory
     * @param  array      $options Options
     * @return boolean    TRUE if it matches the requirements, FALSE otherwise
     */
    public function vote($local, Filesystem $remote, array $options = array());
}
