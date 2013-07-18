<?php

namespace Touki\FTP;

use Touki\FTP\Model\Filesystem;

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
     * @param  Filesystem $remote  Remote File, directory
     * @param  mixed      $local   Local file, resource, directory
     * @param  array      $options Uploader options
     * @return boolean    TRUE on success
     */
    public function upload(Filesystem $remote, $local, array $options = array());
}
