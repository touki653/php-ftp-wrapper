<?php

namespace Touki\FTP;

use Touki\FTP\Model\Filesystem;

/**
 * Creator votable Interface
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
interface CreatorVotableInterface
{
    /**
     * Returns true if given informations matches with the creator
     *
     * @param  Filesystem $remote  Filesystem to create
     * @param  array      $options Creator options
     * @return boolean    TRUE if it matches, FALSE otherwise
     */
    public function vote(Filesystem $remote, array $options = array());
}
