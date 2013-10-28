<?php

namespace Touki\FTP;

use Touki\FTP\Model\Filesystem;

/**
 * Interface for the deleter voter
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
interface DeleterVoterInterface
{
    /**
     * Picks up a deleter voter on given options
     *
     * @param Filesystem $remote  A Filesystem instance
     * @param array      $options An array of options
     *
     * @return DeleterVotableInterface
     */
    public function vote(Filesystem $remote, array $options = array());
}
