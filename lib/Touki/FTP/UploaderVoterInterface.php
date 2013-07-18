<?php

namespace Touki\FTP;

use Touki\FTP\Model\Filesystem;

/**
 * Interface for the uploader voter
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
interface UploaderVoterInterface
{
    /**
     * Returns true if given informations matches with the uploader
     *
     * @param  Filesystem               $remote  The remote component
     * @param  mixed                    $local   The local component
     * @param  array                    $options Uploader's options
     * @return UploaderVotableInterface Instance of the voted uploader
     */
    public function vote(Filesystem $remote, $local, array $options = array());
}
