<?php

namespace Touki\FTP;

/**
 * Connection interface
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
interface ConnectionInterface
{
    /**
     * Returns the connection stream
     *
     * @return resource FTP Connection stream
     */
    public function getStream();
}
