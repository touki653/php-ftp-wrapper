<?php

namespace Touki\FTP;

use Touki\FTP\Model\Filesystem;

/**
 * Interface class to allow a uploader to be chosen
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
interface UploaderVotableInterface
{
    /**
     * Returns true if given informations matches with the uploader
     *
     * @param  Filesystem $remote  Remote file/directory
     * @param  mixed      $local   The local resource/file/directory
     * @param  array      $options Options
     * @return boolean    TRUE if it matches the requirements, FALSE otherwise
     */
    public function vote(Filesystem $remote, $local, array $options = array());
}
