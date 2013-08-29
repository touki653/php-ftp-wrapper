<?php

namespace Touki\FTP;

use Touki\FTP\Model\Filesystem;

/**
 * Deleter votable Interface
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
interface DeleterVotableInterface
{
    /**
     * Returns true if given informations matches with the deleter
     *
     * @param  Filesystem $remote  Filesystem to create
     * @param  array      $options Deleter options
     * @return boolean    TRUE if it matches, FALSE otherwise
     */
    public function vote(Filesystem $remote, array $options = array());
}
